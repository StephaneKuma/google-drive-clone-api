<?php

declare(strict_types=1);

use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\{actingAs, postJson};

test('can upload a file', function () {
    postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    actingAs($user);

    $file = UploadedFile::fake()->create('document.pdf', 100);

    $root = File::query()->userRoot()->first();

    $response = postJson('/api/v1/files', [
        'parent_id' => $root->id,
        'files' => [
            $file
        ],
    ]);

    $root = File::query()->userRoot()->first();

    expect($root->getDescendants()->count())->toBe(1);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
        ]);
});

test('can upload multiple files', function () {
    postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();

    actingAs($user);

    $files = [];

    $numberOfFileToCreate = 3;

    for ($i = 0; $i < $numberOfFileToCreate; $i++) {
        $files[] = UploadedFile::fake()->create("file_$i.txt", 100);
    }

    $root = File::query()->userRoot()->first();

    $response = postJson('/api/v1/files', [
        'parent_id' => $root->id,
        'files' => $files,
    ]);

    $root = File::query()->userRoot()->first();

    expect($root->getDescendants()->count())->toBe(3);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
        ]);
});
