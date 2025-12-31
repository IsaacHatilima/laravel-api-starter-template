<?php

namespace App\DTOs\V1\Command\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class ForgotPasswordRequestDTO extends BaseDTO
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }
}
