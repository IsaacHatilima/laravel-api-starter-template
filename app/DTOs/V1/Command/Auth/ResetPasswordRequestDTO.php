<?php

namespace App\DTOs\V1\Command\Auth;

use App\Http\Requests\V1\Auth\ResetPasswordRequest;

final readonly class ResetPasswordRequestDTO
{
    public function __construct(
        public string $password,
    ) {
    }

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            password: $request->string('password')->value(),
        );
    }
}
