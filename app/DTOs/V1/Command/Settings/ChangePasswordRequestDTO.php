<?php

namespace App\DTOs\V1\Command\Settings;

use App\Http\Requests\V1\Settings\ChangePasswordRequest;

class ChangePasswordRequestDTO
{
    public function __construct(
        public string $currentPassword,
        public string $password,
        public string $passwordConfirmation
    ) {
    }

    public static function fromRequest(ChangePasswordRequest $request): self
    {
        return new self(
            currentPassword: trim(strtolower($request->string('current_password')->value())),
            password: trim($request->string('password')->value()),
            passwordConfirmation: trim($request->string('password_confirmation')->value()),
        );
    }
}
