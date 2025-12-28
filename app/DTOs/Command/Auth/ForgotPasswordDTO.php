<?php

namespace App\DTOs\Command\Auth;

use App\DTOs\BaseDTO;
use App\Http\Requests\Auth\ForgotPasswordRequest;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class ForgotPasswordDTO extends BaseDTO
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
