<?php

declare(strict_types=1);

namespace App\Contracts\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

interface ResetPasswordContract
{
    public function reset(FormRequest $request): JsonResponse;
}
