<?php

namespace App\Actions\V1\Auth;

final readonly class LogoutUserAction
{
    public function execute(): void
    {
        auth('api')->logout();
    }
}
