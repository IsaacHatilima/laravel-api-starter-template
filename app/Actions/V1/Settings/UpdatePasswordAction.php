<?php

namespace App\Actions\V1\Settings;

use App\DTOs\V1\Command\Settings\ChangePasswordRequestDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

final readonly class UpdatePasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(ChangePasswordRequestDTO $dto, User $user): void
    {
        $this->userRepository->update($user, [
            'password' => Hash::make($dto->password),
        ]);
    }
}
