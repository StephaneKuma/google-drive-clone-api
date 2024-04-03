<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\Authentication\ResetPasswordContract;
use App\Http\Requests\Authentication\ResetPassword\ResetPasswordRequest;

/**
 * @group Authentication
 *
 * APIs for authentication
 *
 * @subgroup Reset Password
 * @subgroupDescription Reset the user password.
 */
class ResetPasswordController extends Controller
{
    /**
     * Constructor for the ResetPassword class.
     *
     * @param \App\Contracts\Authentication\ResetPasswordContract $service The service for resetting passwords
     */
    public function __construct(private readonly ResetPasswordContract $service)
    {
    }

    /**
     * Reset password.
     *
     * Reset the given user's password.
     *
     * @header Accept-Language en
     *
     * @response 200 {
     *    "message": "Your password has been reset."
     * }
     *
     * @param \App\Http\Requests\Authentication\ResetPassword\ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        return $this->service->reset($request);
    }
}
