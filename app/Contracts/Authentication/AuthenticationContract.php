<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface AuthenticationContract
{
    /**
     * Create a new user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \App\Models\User
     */
    public function register(FormRequest $request): User;

    /**
     * login in a registered user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @throws \Illuminate\Validation\ValidationException description of exception
     * @return \App\Models\User
     */
    public function login(FormRequest $request): User;

    /**
     * Logout the user by deleting the current access token.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse;
}
