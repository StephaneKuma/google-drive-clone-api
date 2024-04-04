<?php

declare(strict_types=1);

use App\Models\File;
use App\Models\User;

use function Pest\Laravel\{actingAs, postJson};

test('can create a folder', function () {
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

    $response->assertCreated()
        ->assertJsonStructure([
            'data'
        ]);

    $root = File::query()->userRoot()->first();

    expect($root->lft)->toBe(1);
    expect($root->rgt)->toBe(4);
    expect($root->children->count())->toBe(1);
});


test('can not create a folder with invalid parent', function () {
    postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    postJson('/api/v1/register', [
        'name' => 'Test Second User',
        'email' => 'test2@example.com',
        'password' => 'password2',
        'password_confirmation' => 'password2',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    actingAs($user);

    $response = postJson('/api/v1/folders', [
        'parent_id' => 2,
        'name' => 'Test Folder',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('parent_id');
});

test('can not create a folder with already existing name', function () {
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
        'name' => 'Test Folder',
    ]);

    $response = postJson('/api/v1/folders', [
        'parent_id' => $root->id,
        'name' => 'Test Folder',
    ]);

    $response->assertForbidden()
        ->assertJsonStructure([
            'message'
        ]);
});
