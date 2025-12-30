<?php

use function Pest\Laravel\postJson;

it('can refresh a token', function () {
    $user = createUser();
    $token = auth('api')->login($user);

    $response = postJson('/api/v1/auth/refresh-token', [], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => ['token'],
        ]);
});
