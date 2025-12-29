<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;

trait InteractsWithAuth
{
    /**
     * Get the currently authenticated user or fail.
     *
     * * @throws AuthenticationException
     */
    protected function user(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
