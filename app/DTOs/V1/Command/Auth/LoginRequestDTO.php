<?php

namespace App\DTOs\V1\Command\Auth;

use App\Http\Requests\V1\Auth\LoginRequest;

final readonly class LoginRequestDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            email: trim(strtolower($request->string('email')->value())),
            password: trim($request->string('password')->value())
        );
    }
}
