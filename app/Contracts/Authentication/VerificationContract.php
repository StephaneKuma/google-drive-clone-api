<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface VerificationContract
{
    public function verify(FormRequest $request): JsonResponse;

    public function resend(Request $request): JsonResponse;
}
