<?php

use function Pest\Laravel\postJson;

it('user can update profile', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-profile', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'email',
                'profile' => [
                    'first_name',
                    'last_name',
                ],
            ],
        ])
        ->assertJsonPath('data.profile.first_name', 'John')
        ->assertJsonPath('data.profile.last_name', 'Doe');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'test@example.com',
    ]);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
});

it('user cannot update profile with used email', function () {
    createUser([
        'email' => 'some.user@example.com',
        'password' => 'Password123!',
    ]);

    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-profile', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'some.user@example.com',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'The email has already been taken.')
        ->assertJsonPath('errors.email.0', 'The email has already been taken.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'test@example.com',
    ]);
});

it('user requires email verification after email change', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-profile', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'new.email@example.com',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonPath('data.email', 'new.email@example.com')
        ->assertJsonPath('data.email_verified_at', null);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email_verified_at' => null,
    ]);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
});

it('user cannot update profile with invalid data', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-profile', [
        'first_name' => '',
        'last_name' => 'Doe',
        'email' => 'newexample.com',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('errors.first_name.0', 'The first name field is required.')
        ->assertJsonPath('errors.email.0', 'The email field must be a valid email address.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'test@example.com',
    ]);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'first_name' => $user->profile->first_name,
    ]);
});
