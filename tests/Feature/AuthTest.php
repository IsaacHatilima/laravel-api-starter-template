<?php

use function Pest\Laravel\getJson;
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
            'id',
            'email',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

it('can login a user', function () {
    $user = createUser([
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
                'id',
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

it('can get the authenticated user profile', function () {
    $user = loginUser();

    $response = getJson('/api/auth/me');

    $response->assertOk()
        ->assertJsonPath('email', $user->email);
});

it('cannot get profile when unauthenticated', function () {
    $response = getJson('/api/auth/me');

    $response->assertStatus(401);
});

it('can logout a user', function () {
    loginUser();

    $response = postJson('/api/auth/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Successfully logged out']);
});

it('can refresh a token', function () {
    $user = createUser();
    $token = auth('api')->login($user);

    $response = postJson('/api/auth/refresh-token', [], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'token',
            'token_type',
        ]);
});

it('can request a password reset link', function () {
    $user = createUser(['email' => 'reset@example.com']);

    $response = postJson('/api/auth/forgot-password', [
        'email' => 'reset@example.com',
    ]);

    $response->assertOk();
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
