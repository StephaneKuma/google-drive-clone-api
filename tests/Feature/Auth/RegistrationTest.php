<?php

declare(strict_types=1);

use App\Models\File;
use App\Models\User;

use function Pest\Laravel\{postJson};

test('can register a new user', function () {
    $response = postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    expect($user->files()->first()->toArray())->toBe(File::first()->toArray());

    $response->assertCreated()
        ->assertJsonStructure(['data', 'token']);
});
