<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class TwoFactorLoginAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(TwoFactorLoginRequest $request, User $user): string
    {
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

        $this->userRepository->update($user, [
            'last_login_at' => now(),
        ]);

        return $token;
    }
}
