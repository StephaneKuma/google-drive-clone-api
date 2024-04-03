<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Authentication\LoginRequest;
use App\Contracts\Authentication\AuthenticationContract;
use App\Http\Requests\Authentication\RegistrationRequest;

/**
 * @group Authentication
 *
 * APIs for authentication
 */
class AuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Authentication\AuthenticationContract $service
     */
    public function __construct(private readonly AuthenticationContract $service)
    {
    }

    /**
     * Register a new user.
     *
     * Registers a user based on the provided registration request
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional token="1|Ue8Wpv3izBua27OmZAAmk8eofN7XS7iQSzyiLW7c1f96270e"
     *
     * @param \App\Http\Requests\Authentication\RegistrationRequest $request The registration request data
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function register(RegistrationRequest $request): JsonResource
    {
        $user = $this->service->register($request);

        $token = $user->createToken(name: config('app.name'), expiresAt: now()->addUTCHours(3))
            ->plainTextToken;

        return UserResource::make($user)->additional([
            'token' => $token
        ]);
    }

    /**
     *
     * Current user
     *
     * Get the currently authenticated user.
     *
     * @header Accept-Language: en
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @authenticated
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function currentUser(Request $request): JsonResource
    {
        return UserResource::make($request->user());
    }

    /**
     * Login a user
     *
     * Logins a user based on the provided login request
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional token="1|Ue8Wpv3izBua27OmZAAmk8eofN7XS7iQSzyiLW7c1f96270e"
     *
     * @param \App\Http\Requests\Authentication\LoginRequest $request description
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function login(LoginRequest $request): JsonResource
    {
        $user = $this->service->login($request);

        $token = $user->createToken(name: config('app.name'), expiresAt: now()->addUTCHours(3))
            ->plainTextToken;

        return UserResource::make($user)->additional([
            'token' => $token
        ]);
    }

    /**
     * Signs out a user
     *
     * @header Accept-Language en
     *
     * @response 204 {
     * }
     *
     * @authenticated
     *
     * @param \Illuminate\Http\Request $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        return $this->service->logout($request);
    }
}
