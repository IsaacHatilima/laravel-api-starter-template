<?php

namespace App\DTOs\V1\Command\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;

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
