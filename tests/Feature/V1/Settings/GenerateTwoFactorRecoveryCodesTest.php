<?php

use function Pest\Laravel\postJson;

it('requires authentication to generate new 2FA recovery codes', function () {
    $response = postJson('/api/v1/auth/2fa-recovery-codes');

    $response->assertStatus(401);
});

it('cannot generate new 2FA recovery codes if 2FA is not enabled', function () {
    $user = createUser();
    loginUser($user);

    $response = postJson('/api/v1/auth/2fa-recovery-codes');

    $response
        ->assertStatus(422)
        ->assertJsonPath('message', 'Two-factor authentication is not enabled')
        ->assertJsonPath('errors.two_factor.0', 'Two-factor authentication is not enabled');
});

it('can generate new 2FA recovery codes', function () {
    $user = createUser();
    loginUser($user);

    // Enable 2FA first
    postJson('/api/v1/auth/2fa-enable');
    $user->refresh();
    $oldRecoveryCodes = $user->two_factor_recovery_codes;

    $response = postJson('/api/v1/auth/2fa-recovery-codes');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'recovery_codes',
            ],
        ])
        ->assertJsonPath('message', '2FA recovery codes regenerated successfully');

    $user->refresh();

    expect($user->two_factor_recovery_codes)->not->toBe($oldRecoveryCodes)
        ->and($response->json('data.recovery_codes'))->toBeArray()
        ->and($response->json('data.recovery_codes'))->toHaveCount(8);
});
