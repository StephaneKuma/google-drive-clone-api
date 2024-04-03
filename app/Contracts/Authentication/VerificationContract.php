<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface VerificationContract
{
    /**
     * Verify the email of the user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(FormRequest $request): JsonResponse;

    /**
     * Resend the email verification notification.
     *
     * @param Request $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse;
}
