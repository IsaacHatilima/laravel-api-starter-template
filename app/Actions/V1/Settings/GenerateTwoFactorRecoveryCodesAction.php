<?php

namespace App\Actions\V1\Settings;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use JsonException;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

final readonly class GenerateTwoFactorRecoveryCodesAction
{
    public function __construct(
        private GenerateNewRecoveryCodes $generateNewRecoveryCodes
    ) {
    }

    /**
     * @return array<int, string>
     *
     * @throws JsonException|ValidationException
     */
    public function execute(User $user): array
    {
        $this->guardTwoFactorEnabled($user);

        ($this->generateNewRecoveryCodes)($user);

        return $this->getDecodedRecoveryCodes($user);
    }

    /**
     * @return array<int, string>
     *
     * @throws JsonException|ValidationException
     */
    private function getDecodedRecoveryCodes(User $user): array
    {
        $raw = $user->two_factor_recovery_codes
            ?? throw ValidationException::withMessages(['two_factor' => 'Two-factor recovery codes are missing']);

        $decoded = decrypt($raw);

        if (! is_string($decoded)) {
            throw ValidationException::withMessages(['two_factor' => 'Invalid recovery codes payload']);
        }

        $codes = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($codes)) {
            throw ValidationException::withMessages(['two_factor' => 'Invalid recovery codes payload']);
        }

        return array_map(function (mixed $value): string {
            if (! is_scalar($value) && ! is_null($value)) {
                throw ValidationException::withMessages(['two_factor' => 'Invalid recovery code type encountered']);
            }

            return (string) $value;
        }, array_values($codes));
    }

    private function guardTwoFactorEnabled(User $user): void
    {
        if ($user->two_factor_secret === null) {
            throw ValidationException::withMessages(['two_factor' => 'Two-factor authentication is not enabled']);
        }
    }
}
