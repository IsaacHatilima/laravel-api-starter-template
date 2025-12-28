<?php

use function Pest\Laravel\postJson;

it('can register a user', function () {
    $response = postJson('/api/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'public_id',
            'email',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

it('fails to register with existing email', function () {
    createUser(['email' => 'existing@example.com']);

    $response = postJson('/api/auth/register', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('fails registration with weak password', function () {
    $response = postJson('/api/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'weak@example.com',
        'password' => '123',
        'password_confirmation' => '123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
