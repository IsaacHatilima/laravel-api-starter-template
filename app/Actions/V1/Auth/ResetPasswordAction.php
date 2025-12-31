<?php

namespace App\Actions\V1\Auth;

use App\DTOs\V1\Command\Auth\ResetPasswordRequestDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final readonly class ResetPasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(ResetPasswordRequestDTO $dto, User $user): void
    {
        $user = $this->userRepository->update(
            $user,
            [
                'password' => Hash::make($dto->password),
                'remember_token' => Str::random(60),
            ]
        );

        event(new PasswordReset($user));
    }
}
