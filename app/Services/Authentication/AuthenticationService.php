<?php

declare(strict_types=1);

namespace App\Services\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\AuthenticationContract;
use App\Repositories\Authentication\AuthenticationRepository;

class AuthenticationService implements AuthenticationContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\Authentication\AuthenticationRepository $repository
     */
    public function __construct(private readonly AuthenticationRepository $repository)
    {
    }

    /**
     * Create a new user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \App\Models\User
     */
    public function register(FormRequest $request): User
    {
        return $this->repository->register($request);
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
        return $this->repository->login($request);
    }

    /**
     * Logout the user by deleting the current access token.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        return $this->repository->logout($request);
    }
}
