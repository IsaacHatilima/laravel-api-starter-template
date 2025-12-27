<?php

namespace App\Actions\Auth;

readonly class LogoutUserAction
{
    public function execute(): void
    {
        auth('api')->logout();
    }
}
