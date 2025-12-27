<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class DisableTwoFactorAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(User $user): User
    {
        return DB::transaction(function () use ($user): User {
            if (
                $user->two_factor_secret !== null
                && $user->two_factor_recovery_codes !== null
            ) {
                $user = $this->userRepository->update($user, [
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null,
                ]);
            }

            return $user;
        });
    }
}
