<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface ForgotPasswordContract
{
    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(FormRequest $request): JsonResponse;
}
