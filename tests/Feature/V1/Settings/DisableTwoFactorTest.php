<?php

use function Pest\Laravel\postJson;

it('requires authentication to disable 2FA', function () {
    $response = postJson('/api/v1/auth/2fa-disable');

    $response->assertStatus(401);
});

it('can disable 2FA', function () {
    $user = createUser([
        'two_factor_secret' => 'secret',
        'two_factor_recovery_codes' => encrypt(json_encode(['code1'])),
        'two_factor_confirmed_at' => now(),
    ]);
    loginUser($user);

    $response = postJson('/api/v1/auth/2fa-disable');

    $response
        ->assertStatus(200)
        ->assertJsonPath('message', '2FA disabled successfully');

    $user->refresh();

    expect($user->two_factor_secret)->toBeNull()
        ->and($user->two_factor_recovery_codes)->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();
});

it('does nothing if 2FA is not enabled', function () {
    $user = createUser([
        'two_factor_secret' => null,
        'two_factor_recovery_codes' => null,
        'two_factor_confirmed_at' => null,
    ]);
    loginUser($user);

    $response = postJson('/api/v1/auth/2fa-disable');

    $response
        ->assertStatus(200)
        ->assertJsonPath('message', '2FA disabled successfully');

    $user->refresh();

    expect($user->two_factor_secret)->toBeNull();
});
