<?php

namespace App\Actions\V1\Auth;

use App\DTOs\V1\Command\Auth\LoginDTO;
use App\DTOs\V1\Read\User\TwoFactorAuthDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class LoginUserAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function execute(LoginDTO $dto): TwoFactorAuthDTO
    {
        $user = User::query()->where('email', $dto->email)->first();

        $user = $this->ensureUserIsValid($user, $dto);

        if ($this->shouldUseTwoFactor($user)) {
            return new TwoFactorAuthDTO($user, null, true);
        }

        $token = JWTAuth::fromUser($user);

        $this->userRepository->update($user, [
            'last_login_at' => now(),
        ]);

        return new TwoFactorAuthDTO($user, $token, false);
    }

    private function ensureUserIsValid(?User $user, LoginDTO $dto): User
    {
        if (! $user || ! auth()->validate(['email' => $dto->email, 'password' => $dto->password])) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect']]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages(['email' => ['Account is not active']]);
        }

        if ($user->email_verified_at === null) {
            throw ValidationException::withMessages(['email' => ['Email is not verified']]);
        }

        return $user;
    }

    private function shouldUseTwoFactor(User $user): bool
    {
        return $user->two_factor_secret && $user->two_factor_confirmed_at;
    }
}
