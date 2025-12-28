<?php

use function Pest\Laravel\getJson;

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
