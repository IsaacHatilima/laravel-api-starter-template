<?php

namespace App\Actions\V1\Auth;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class RefreshTokenAction
{
    public function execute(string $token): string
    {
        return JWTAuth::setToken($token)->refresh();
    }
}
