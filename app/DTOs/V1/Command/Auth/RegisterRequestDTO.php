<?php

namespace App\DTOs\V1\Command\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\V1\Auth\RegisterRequest;
use Illuminate\Support\Str;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class RegisterRequestDTO extends BaseDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
    ) {
    }

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(
            firstName: trim(Str::title($request->string('first_name')->value())),
            lastName: trim(Str::title($request->string('last_name')->value())),
            email: trim(strtolower($request->string('email')->value())),
            password: trim($request->string('password')->value()),
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
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
