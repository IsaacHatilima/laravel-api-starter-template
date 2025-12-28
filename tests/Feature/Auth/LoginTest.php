<?php

use function Pest\Laravel\postJson;

it('can login a user', function () {
    createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response = postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'user' => [
                'public_id',
                'email',
            ],
            'token',
            'token_type',
        ]);
});

it('cannot login with invalid credentials', function () {
    createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    $response = postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});
