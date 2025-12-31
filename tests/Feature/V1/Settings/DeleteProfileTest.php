<?php

use function Pest\Laravel\postJson;

it('user can delete profile', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/delete-profile', [
        'password' => 'Password123!',
    ]);

    $response
        ->assertStatus(204);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('user cannot delete profile with wrong', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/delete-profile', [
        'password' => 'Password123',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('errors.password.0', 'The password is incorrect.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});
