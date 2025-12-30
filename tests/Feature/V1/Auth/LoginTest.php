<?php

use function Pest\Laravel\postJson;

it('can login a user', function () {
    createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'user' => [
                    'public_id',
                    'email',
                ],
                'token',
            ],
        ]);
});

it('cannot login with invalid credentials', function () {
    createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.email.0', 'The provided credentials are incorrect');
});
