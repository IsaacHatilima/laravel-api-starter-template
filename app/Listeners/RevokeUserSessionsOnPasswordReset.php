<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

class RevokeUserSessionsOnPasswordReset
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        /** @var User $user */
        $user = $event->user;

        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        $user->update([
            'token_version' => $user->token_version + 1,
        ]);
    }
}
