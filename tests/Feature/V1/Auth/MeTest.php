<?php

use function Pest\Laravel\getJson;

it('can get the authenticated user profile', function () {
    loginUser();

    $response = getJson('/api/v1/auth/me');

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
        ->assertJsonPath('success', true);
});

it('cannot get profile when unauthenticated', function () {
    $response = getJson('/api/v1/auth/me');

    $response->assertStatus(401);
});
