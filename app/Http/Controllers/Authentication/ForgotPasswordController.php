<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\Authentication\ForgotPasswordContract;
use App\Http\Requests\Authentication\ForgotPassword\SendResetLinkEmailRequest;

/**
 * @group Authentication
 *
 * APIs for authentication
 *
 * @subgroup Forgot Password
 * @subgroupDescription Send a reset link to the given user.
 *
 * @authenticated
 */
class ForgotPasswordController extends Controller
{
    /**
     * A constructor for the forgot password controller class.
     *
     * @param \App\Contracts\Authentication\ForgotPasswordContract $service The service for handling forgot password functionality.
     */
    public function __construct(private readonly ForgotPasswordContract $service)
    {
    }

    /**
     * Password reset link.
     *
     * Send a reset link to the given user.
     *
     * @header Accept-Language en
     *
     * @response 200 {
     *    "message": "We have e-mailed your password reset link."
     * }
     *
     * @param \App\Http\Requests\Authentication\ForgotPassword\SendResetLinkEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(SendResetLinkEmailRequest $request): JsonResponse
    {
        return $this->service->sendResetLinkEmail($request);
    }
}
