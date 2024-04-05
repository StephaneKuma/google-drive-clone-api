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

test('can create a subfolder', function () {
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

    $response = postJson('/api/v1/folders', [
        'parent_id' => $homeFolder->id,
        'name' => 'Documents',
    ]);

    $documentsFolder = File::query()->where('name', 'Documents')
        ->where('parent_id', $homeFolder->id)
        ->first();

    expect($documentsFolder->path)->toBe('home/documents');

    $response->assertCreated()
        ->assertJsonStructure([
            'data'
        ]);

    $root = File::query()->userRoot()->first();

    expect($root->lft)->toBe(1);
    expect($root->rgt)->toBe(6);
    expect($root->getDescendants()->count())->toBe(2);
});


test('can not create a folder under another user folder', function () {
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

    $response->assertForbidden()
        ->assertJsonStructure([
            'message'
        ]);
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

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('name');
});
