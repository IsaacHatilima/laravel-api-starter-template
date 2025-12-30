<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use App\Repositories\UserRepository;

final readonly class EndAllSessionsAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(User $user): void
    {
        $this->userRepository->update(
            $user,
            [
                'token_version' => $user->token_version + 1,
            ]
        );
    }
}
