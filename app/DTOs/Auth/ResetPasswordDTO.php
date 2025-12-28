<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\Auth\ResetPasswordRequest;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class ResetPasswordDTO extends BaseDTO
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'password' => $this->password,
        ];
    }
}
