<?php

namespace App\Actions\Auth;

final readonly class LogoutUserAction
{
    public function execute(): void
    {
        auth('api')->logout();
    }
}
