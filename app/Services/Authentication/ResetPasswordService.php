<?php

declare(strict_types=1);

namespace App\Services\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\ResetPasswordContract;
use App\Repositories\Authentication\ResetPasswordRepository;

class ResetPasswordService implements ResetPasswordContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\Authentication\ResetPasswordRepository $repository
     */
    public function __construct(private readonly ResetPasswordRepository $repository)
    {
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(FormRequest $request): JsonResponse
    {
        return $this->repository->reset($request);
    }
}
