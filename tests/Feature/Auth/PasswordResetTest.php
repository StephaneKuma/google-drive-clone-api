<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{postJson};

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = postJson('/api/v1/password/email', ['email' => $user->email]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
        ]);
    Notification::assertSentTo($user, ResetPassword::class);
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    postJson('/api/v1/password/email', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        $response = postJson('/api/v1/password/reset', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        return true;
    });
});
