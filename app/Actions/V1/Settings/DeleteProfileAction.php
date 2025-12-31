<?php

namespace App\Actions\V1\Settings;

use App\Models\User;
use App\Repositories\UserRepository;

final readonly class DeleteProfileAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
