<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final readonly class EmailVerificationAction
{
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

        $user = User::query()->where('public_id', $request->query('id'))->first();

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
