<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class VerifyResetPasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function execute(string $id): User
    {
        $user = $this->userRepository->findByPublicId($id);

        if (! $user) {
            throw new ModelNotFoundException('User not found.');
        }

        $passwordToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if (! $passwordToken) {
            throw new ModelNotFoundException('Invalid password reset token.');
        }

        return $user;
    }
}
