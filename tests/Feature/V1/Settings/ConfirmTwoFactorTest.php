<?php

use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

use function Pest\Laravel\mock;
use function Pest\Laravel\postJson;

it('requires authentication to confirm 2FA', function () {
    $response = postJson('/api/v1/auth/2fa-confirm', ['code' => '123456']);

    $response->assertStatus(401);
});

it('can confirm 2FA with valid code', function () {
    $user = createUser();
    loginUser($user);

    mock(ConfirmTwoFactorAuthentication::class)
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('__invoke')
        ->once()
        ->withArgs(function ($u, $code) use ($user) {
            return $u->id === $user->id && $code === '123456';
        })
        ->andReturnNull();

    $response = postJson('/api/v1/auth/2fa-confirm', [
        'code' => '123456',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonPath('message', '2FA confirmed successfully');
});

it('returns error with invalid verification code', function () {
    $user = createUser();
    loginUser($user);

    // Mocking the Fortify action to throw ValidationException as it would when code is invalid
    mock(ConfirmTwoFactorAuthentication::class)
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('__invoke')
        ->once()
        ->andThrow(ValidationException::withMessages(['code' => ['Invalid verification code']]));

    $response = postJson('/api/v1/auth/2fa-confirm', [
        'code' => 'invalid',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('message', 'Invalid verification code');
});

it('validates that code is required', function () {
    $user = createUser();
    loginUser($user);

    $response = postJson('/api/v1/auth/2fa-confirm', []);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);
});
