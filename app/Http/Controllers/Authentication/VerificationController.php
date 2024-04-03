<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\Authentication\VerificationContract;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/**
 * @group Authentication
 *
 * APIs for authentication
 *
 * @subgroup Verify Email
 * @subgroupDescription Verify user email.
 */
class VerificationController extends Controller
{
    /**
     * Constructor for the verification class.
     *
     * @param \App\Contracts\Authentication\VerificationContract $service The service for verifying users.
     */
    public function __construct(private readonly VerificationContract $service)
    {
    }

    /**
     * Verify email
     *
     * Verify the email of the user.
     *
     * @header Accept-Language en
     *
     * @urlParam id required The ID of the user. Example: 1
     * @urlParam hash required The hash of the user. Example: 56Z83cdafEvb1P4VheDga6k
     *
     * @response 200 {
     *    "message": "Email successfully verified"
     * }
     *
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        return $this->service->verify($request);
    }

    /**
     * Resend email
     *
     * Resend the email verification notification.
     *
     * @header Accept-Language en
     *
     * @response 200 {
     *    "message": "Email verification link sent on your email id"
     * }
     *
     * @param \Illuminate\Http\Request $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        return $this->service->resend($request);
    }
}
