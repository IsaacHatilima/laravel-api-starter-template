<?php

use function Pest\Laravel\postJson;

it('requires two-factor code if enabled during login', function () {
    $user = createUser([
        'email' => '2fa@example.com',
        'password' => 'Password123!',
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery'])),
        'two_factor_confirmed_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = postJson('/api/auth/login', [
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

    $response = postJson('/api/auth/login', [
        'email' => '2fa-success@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk();
    $response->assertJson(['two_factor' => true]);

    // Mock Fortify's check for code
    // Since we are using Fortify's TwoFactorLoginRequest, it uses TwoFactorAuthenticationProvider
    // In a real test we'd need a valid OTP, but we can try to use a recovery code
    $response = postJson('/api/auth/two-factor-login', [
        'recovery_code' => 'recovery',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['user', 'token']);
});

it('does not require two-factor code if disabled during login', function () {
    $user = createUser([
        'email' => 'no-2fa@example.com',
        'password' => 'Password123!',
        'email_verified_at' => now(),
    ]);

    $response = postJson('/api/auth/login', [
        'email' => 'no-2fa@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk();
});
