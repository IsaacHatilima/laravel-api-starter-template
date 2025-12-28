<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
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
        $user = $this->userRepository->find($id);

        if (! $user) {
            throw ValidationException::withMessages([
                'id' => ['User not found'],
            ]);
        }

        $passwordToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if (! $passwordToken) {
            throw ValidationException::withMessages([
                'id' => ['User not found'],
            ]);
        }

        return $user;
    }
}
