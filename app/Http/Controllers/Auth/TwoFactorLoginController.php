<?php

namespace App\Http\Controllers\Auth;

use App\DTOs\Read\User\AuthResponseDTO;
use App\DTOs\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TwoFactorLoginController extends Controller
{
    public function __invoke(TwoFactorLoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->challengedUser();

        $code = $request->validRecoveryCode();

        if ($code) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            event(new TwoFactorAuthenticationFailed($user));

            return response()->json([
                'message' => 'The provided two-factor authentication code was invalid.',
            ], 422);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        $token = JWTAuth::fromUser($user);

        $response = new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: $token,
        );

        return response()->json($response);
    }
}
