<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;

final readonly class EmailVerificationAction
{
    /**
     * @return array{message: string}
     */
    public function execute(string $id): array
    {
        $user = User::query()->where('public_id', $id)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                ['User not found'],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return ['message' => 'Email is already verified'];
        }

        $user->markEmailAsVerified();

        return ['message' => 'Email verified successfully'];
    }
}
