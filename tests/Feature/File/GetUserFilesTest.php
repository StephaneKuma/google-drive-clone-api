<?php

declare(strict_types=1);

use App\Models\File;
use App\Models\User;

use function Pest\Laravel\{actingAs, getJson, postJson};

test('can get user files', function () {
    postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    actingAs($user);

    $root = File::query()->userRoot()->first();

    $response = postJson('/api/v1/folders', [
        'parent_id' => $root->id,
        'name' => 'Test Folder',
    ]);

    $response = getJson('/api/v1/files');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
});


test('can get user files from a subfolder', function () {
    postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    actingAs($user);

    $root = File::query()->userRoot()->first();

    postJson('/api/v1/folders', [
        'parent_id' => $root->id,
        'name' => 'Home',
    ]);

    $homeFolder = File::query()->where('name', 'Home')
        ->where('parent_id', $root->id)
        ->first();

    postJson('/api/v1/folders', [
        'parent_id' => $homeFolder->id,
        'name' => 'Documents',
    ]);

    $documentsFolder = File::query()->where('name', 'Documents')
        ->where('parent_id', $homeFolder->id)
        ->first();

    $response = getJson('/api/v1/files/' . $documentsFolder->path);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
});
