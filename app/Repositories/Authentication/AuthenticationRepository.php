<?php

declare(strict_types=1);

namespace App\Repositories\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Contracts\Authentication\AuthenticationContract;

class AuthenticationRepository implements AuthenticationContract
{
    /**
     * Create a new user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \App\Models\User
     */
    public function register(FormRequest $request): User
    {
        /** @var array<int, string> $validated */
        $validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = User::create($validated);

        return $user;
    }

    /**
     * login in a registered user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @throws \Illuminate\Validation\ValidationException description of exception
     * @return \App\Models\User
     */
    public function login(FormRequest $request): User
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = User::where('email', $validated['email'])->first();

        return $user;
    }

    /**
     * Logout the user by deleting the current access token.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @phpstan-ignore-next-line */
        $request->user()->currentAccessToken()->delete();

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
