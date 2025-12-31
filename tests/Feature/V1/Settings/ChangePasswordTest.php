<?php

use function Pest\Laravel\postJson;

it('user can change password', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-password', [
        'current_password' => 'Password123!',
        'password' => 'Password12#',
        'password_confirmation' => 'Password12#',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Password changed successfully');

    $user->refresh();
    $this->assertTrue(Hash::check('Password12#', $user->password));
});

it('user cannot change password with invalid current password', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-password', [
        'current_password' => 'Password1#',
        'password' => 'Password12#',
        'password_confirmation' => 'Password12#',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'The password is incorrect.')
        ->assertJsonPath('errors.current_password.0', 'The password is incorrect.');

    $user->refresh();
    $this->assertFalse(Hash::check('Password12#', $user->password));
});

it('user cannot change password with invalid password format', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-password', [
        'current_password' => 'Password1#',
        'password' => 'Password',
        'password_confirmation' => 'Password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('errors.password', [
            'The password field must contain at least one symbol.',
            'The password field must contain at least one number.',
        ]);

    $user->refresh();
    $this->assertFalse(Hash::check('Password', $user->password));
});

it('user cannot change password with mismatched passwords', function () {
    $user = createUser([
        'email' => 'test@example.com',
        'password' => 'Password123!',
    ]);

    loginUser($user);

    $response = postJson('/api/v1/settings/update-password', [
        'current_password' => 'Password1#',
        'password' => 'Password1',
        'password_confirmation' => 'Password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('errors.password.0',
            'The password field confirmation does not match.',
        );

    $user->refresh();
    $this->assertFalse(Hash::check('Password1', $user->password));
});
