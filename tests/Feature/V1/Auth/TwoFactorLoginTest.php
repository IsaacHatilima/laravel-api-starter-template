<?php

use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

use function Pest\Laravel\postJson;

it('requires two-factor code if enabled during login', function () {
    createUser([
        'email' => '2fa@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery'])),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => '2fa@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk();
    $response->assertJson(['two_factor' => true]);
});

it('can login with two-factor code', function () {
    $user = createUser([
        'email' => '2fa-success@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery'])),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    postJson('/api/v1/auth/login', [
        'email' => '2fa-success@example.com',
        'password' => 'Password123!',
    ])->assertOk();

    $mock = $this->mock(TwoFactorAuthenticationProvider::class);
    $mock->shouldReceive('verify')->andReturn(true);

    $response = postJson('/api/v1/auth/two-factor-login', [
        'code' => '123456',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['user', 'token']);
    expect($response->json('user.email'))->toBe($user->email);
});

it('can login with recovery code', function () {
    $user = createUser([
        'email' => 'recovery@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code'])),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    postJson('/api/v1/auth/login', [
        'email' => 'recovery@example.com',
        'password' => 'Password123!',
    ])->assertOk();

    $response = postJson('/api/v1/auth/two-factor-login', [
        'recovery_code' => 'recovery-code',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['user', 'token']);

    $user->refresh();
    expect($user->recoveryCodes())->not->toContain('recovery-code');
});

it('fails with invalid two-factor code', function () {
    createUser([
        'email' => '2fa-fail@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    postJson('/api/v1/auth/login', [
        'email' => '2fa-fail@example.com',
        'password' => 'Password123!',
    ])->assertOk();

    $mock = $this->mock(TwoFactorAuthenticationProvider::class);
    $mock->shouldReceive('verify')->andReturn(false);

    $response = postJson('/api/v1/auth/two-factor-login', [
        'code' => 'wrong',
    ]);

    $response->assertStatus(422);
    $response->assertJson(['message' => 'The provided two-factor authentication code was invalid.']);
});

it('fails with invalid recovery code', function () {
    createUser([
        'email' => 'recovery-fail@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery'])),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    postJson('/api/v1/auth/login', [
        'email' => 'recovery-fail@example.com',
        'password' => 'Password123!',
    ])->assertOk();

    $response = postJson('/api/v1/auth/two-factor-login', [
        'recovery_code' => 'wrong',
    ]);

    $response->assertStatus(422);
});

it('fails if not challenged', function () {
    $response = postJson('/api/v1/auth/two-factor-login', [
        'code' => '123456',
    ]);

    $response->assertStatus(422);
});

it('does not require two-factor code if disabled during login', function () {
    createUser([
        'email' => 'no-2fa@example.com',
        'password' => 'Password123!',
        'email_verified_at' => now(),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'no-2fa@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['user', 'token']);
});
