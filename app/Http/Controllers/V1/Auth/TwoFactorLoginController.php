<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\TwoFactorLoginAction;
use App\DTOs\V1\Read\User\AuthResponseDTO;
use App\DTOs\V1\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorLoginController extends Controller
{
    public function __construct(private readonly TwoFactorLoginAction $twoFactorLoginAction)
    {
    }

    public function __invoke(TwoFactorLoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->challengedUser();

        $token = $this->twoFactorLoginAction->execute($request, $user);

        $response = new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: $token,
        );

        return response()->json($response);
    }
}
