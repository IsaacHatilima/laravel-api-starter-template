<?php

namespace App\Actions\Auth;

use App\DTOs\Command\Auth\ResetPasswordDTO;
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

    public function execute(ResetPasswordDTO $dto, User $user): string
    {
        $user = $this->userRepository->update(
            $user,
            [
                'password' => Hash::make($dto->password),
                'remember_token' => Str::random(60),
            ]
        );

        event(new PasswordReset($user));

        return __('Password reset successful');
    }
}
