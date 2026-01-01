<?php

namespace App\DTOs\V1\Command\Auth;

use App\Http\Requests\V1\Auth\ForgotPasswordRequest;

final readonly class ForgotPasswordRequestDTO
{
    public function __construct(
        public string $email,
    ) {
    }

    public static function fromRequest(ForgotPasswordRequest $request): self
    {
        return new self(
            email: trim(strtolower($request->string('email')->value())),
        );
    }
}
