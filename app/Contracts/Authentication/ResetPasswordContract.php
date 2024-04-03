<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface ResetPasswordContract
{
    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(FormRequest $request): JsonResponse;
}
