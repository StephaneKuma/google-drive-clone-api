<?php

declare(strict_types=1);

use function Pest\Laravel\{postJson};

test('can register a new user', function () {
    $response = postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated()
        ->assertJsonStructure(['data', 'token']);
});
