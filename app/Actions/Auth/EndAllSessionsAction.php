<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\UserRepository;

final readonly class EndAllSessionsAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(User $user): string
    {
        $this->userRepository->update(
            $user,
            [
                'token_version' => $user->token_version + 1,
            ]
        );

        return __('All sessions ended');
    }
}
