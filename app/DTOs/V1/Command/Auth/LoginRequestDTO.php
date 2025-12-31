<?php

namespace App\DTOs\V1\Command\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\V1\Auth\LoginRequest;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class LoginRequestDTO extends BaseDTO
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

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
