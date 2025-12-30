<?php

namespace App\Actions\V1\Auth;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final readonly class EmailVerificationAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(Request $request): void
    {
        $expires = Carbon::createFromTimestamp(
            (int) $request->query('expires')
        );

        if (now()->greaterThan($expires)) {
            throw ValidationException::withMessages([
                'link' => ['Link expired. Please request a new one'],
            ]);
        }

        $user = $this->userRepository->findOne(['public_id' => $request->query('id')]);

        if (! $user) {
            throw new ModelNotFoundException('User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'link' => ['Link already used'],
            ]);
        }

        $user->markEmailAsVerified();
    }
}
