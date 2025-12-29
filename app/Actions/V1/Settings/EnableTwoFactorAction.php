<?php

namespace App\Actions\V1\Settings;

use App\Models\User;
use JsonException;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use RuntimeException;

final readonly class EnableTwoFactorAction
{
    public function __construct(
        private EnableTwoFactorAuthentication $enableTwoFactors
    ) {
    }

    /**
     * @return array{qr_code: string, secret: string, recovery_codes: array<int, string>}
     *
     * @throws JsonException
     * @throws RuntimeException
     */
    public function execute(User $user): array
    {
        $this->guardNotAlreadyEnabled($user);

        ($this->enableTwoFactors)($user);

        if ($user->two_factor_recovery_codes === null || $user->two_factor_secret === null) {
            throw new RuntimeException('Two-factor authentication failed to enable');
        }

        return [
            'qr_code' => $user->twoFactorQrCodeSvg(),
            'secret' => $user->two_factor_secret,
            'recovery_codes' => $this->getDecodedRecoveryCodes($user),
        ];
    }

    /**
     * @return array<int, string>
     *
     * @throws JsonException|RuntimeException
     */
    private function getDecodedRecoveryCodes(User $user): array
    {
        $raw = $user->two_factor_recovery_codes
            ?? throw new RuntimeException('Two-factor recovery codes are missing');

        $decoded = decrypt($raw);

        if (! is_string($decoded)) {
            throw new RuntimeException('Invalid recovery codes payload');
        }

        $codes = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($codes)) {
            throw new RuntimeException('Invalid recovery codes payload');
        }

        return array_map(function (mixed $value): string {
            if (! is_scalar($value) && ! is_null($value)) {
                throw new RuntimeException('Invalid recovery code type encountered');
            }

            return (string) $value;
        }, array_values($codes));
    }

    private function guardNotAlreadyEnabled(User $user): void
    {
        if ($user->two_factor_secret !== null) {
            throw new RuntimeException('Two-factor authentication is already enabled');
        }
    }
}
