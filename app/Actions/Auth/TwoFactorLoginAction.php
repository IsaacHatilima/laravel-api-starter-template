<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;
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

    /**
     * @throws ValidationException
     */
    public function execute(TwoFactorLoginRequest $request, User $user): string
    {
        $code = $request->validRecoveryCode();

        if ($code) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            event(new TwoFactorAuthenticationFailed($user));

            throw ValidationException::withMessages([
                'code' => ['The provided two-factor authentication code was invalid.'],
            ]);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        $token = JWTAuth::fromUser($user);

        $this->userRepository->update($user, [
            'last_login_at' => now(),
        ]);

        return $token;
    }
}
