<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\{postJson, actingAs};

test('can log in a user', function () {
    $user = User::factory()->create();

    $response = postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['data', 'token']);
});

test('can not log in a user with invalid password', function () {
    $user = User::factory()->create();

    $response = postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrorFor('email');
});

test('can log out a user', function () {
    /** @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable $user */
    $user = User::factory()->create();

    actingAs($user);

    $response = postJson('/api/v1/logout');

    $response->assertNoContent();
});
