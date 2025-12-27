<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\Auth\ResetPasswordRequest;

/**
 * @extends BaseDTO<string, mixed>
 */
readonly class ResetPasswordDTO extends BaseDTO
{
    public function __construct(
        public string $password,
        public string $passwordConfirmation,
    ) {
    }

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            password: $request->string('password')->value(),
            passwordConfirmation: $request->string('password_confirmation')->value(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ];
    }
}
