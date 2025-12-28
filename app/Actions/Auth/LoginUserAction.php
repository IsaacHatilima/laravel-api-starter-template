<?php

namespace App\Actions\Auth;

use App\DTOs\Command\Auth\LoginDTO;
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
    public function execute(LoginDTO $dto): string|User
    {
        $user = User::query()->where('email', $dto->email)->first();

        $user = $this->ensureUserIsValid($user, $dto);

        if ($this->shouldUseTwoFactor($user)) {
            return $user;
        }

        $token = JWTAuth::fromUser($user);

        $this->userRepository->update($user, [
            'last_login_at' => now(),
        ]);

        return $token;
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
