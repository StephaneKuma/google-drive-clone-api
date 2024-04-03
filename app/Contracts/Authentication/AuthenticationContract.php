<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface AuthenticationContract
{
    public function register(FormRequest $request): User;

    public function login(FormRequest $request): User;

    public function logout(Request $request): JsonResponse;
}
