<?php

use function Pest\Laravel\postJson;

it('can logout a user', function () {
    loginUser();

    $response = postJson('/api/v1/auth/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Successfully logged out']);
});
