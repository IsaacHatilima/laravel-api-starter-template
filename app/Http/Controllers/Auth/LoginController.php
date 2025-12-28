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

        if ($result->requiresTwoFactor) {
            $request->session()->put([
                'login.id' => $result->user?->getKey(),
                'login.remember' => $request->boolean('remember'),
            ]);

            return response()->json(['two_factor' => true]);
        }

        /** @var User $user */
        $user = $result->user;

        $response = new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: (string) $result->token,
        );

        return response()->json($response);
    }
}
