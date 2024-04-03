<?php

namespace App\Services\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\ForgotPasswordContract;
use App\Repositories\Authentication\ForgotPasswordRepository;

class ForgotPasswordService implements ForgotPasswordContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\Authentication\ForgotPasswordRepository $repository
     */
    public function __construct(private readonly ForgotPasswordRepository $repository)
    {
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(FormRequest $request): JsonResponse
    {
        return $this->repository->sendResetLinkEmail($request);
    }
}
