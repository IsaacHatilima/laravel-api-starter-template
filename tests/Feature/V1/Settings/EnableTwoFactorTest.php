<?php

use function Pest\Laravel\postJson;

it('requires authentication to enable 2FA', function () {
    $response = postJson('/api/v1/auth/2fa-enable');

    $response->assertStatus(401);
});

it('can enable 2FA', function () {
    $user = createUser();
    loginUser($user);

    $response = postJson('/api/v1/auth/2fa-enable');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'qr_code',
            'recovery_codes',
        ])
        ->assertJsonPath('message', '2FA enabled. Please scan QR code and confirm with a code');

    $user->refresh();

    expect($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_recovery_codes)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();
});

it('cannot enable 2FA if already enabled', function () {
    $user = createUser();
    loginUser($user);

    postJson('/api/v1/auth/2fa-enable');

    $response = postJson('/api/v1/auth/2fa-enable');

    $response
        ->assertStatus(409)
        ->assertJsonPath('message', 'Two-factor authentication is already enabled');
});
