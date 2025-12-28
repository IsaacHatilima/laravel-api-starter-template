<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginUserAction;
use App\DTOs\Command\Auth\LoginDTO;
use App\DTOs\Read\User\AuthResponseDTO;
use App\DTOs\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class LoginController extends Controller
{
    public function __construct(private readonly LoginUserAction $loginAction)
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request);

        try {
            $result = $this->loginAction->execute($dto);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        if ($result instanceof User) {
            $request->session()->put([
                'login.id' => $result->getKey(),
                'login.remember' => $request->boolean('remember'),
            ]);

            return response()->json(['two_factor' => true]);
        }

        /** @var string $token */
        $token = $result;

        /** @var JWTGuard $guard */
        $guard = auth('api');

        /** @var User|null $user */
        $user = $guard->setToken($token)->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $response = new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: $token,
        );

        return response()->json($response);
    }
}
