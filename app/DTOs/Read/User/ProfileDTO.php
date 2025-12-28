<?php

namespace App\DTOs\Read\User;

use App\DTOs\BaseDTO;
use App\Models\Profile;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class ProfileDTO extends BaseDTO
{
    public function __construct(
        public string $publicId,
        public string $firstName,
        public string $lastName,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public static function fromModel(Profile $profile): self
    {
        return new self(
            publicId: $profile->public_id,
            firstName: $profile->first_name,
            lastName: $profile->last_name,
            createdAt: $profile->created_at?->toISOString(),
            updatedAt: $profile->updated_at?->toISOString(),
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
            'public_id' => $this->publicId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
