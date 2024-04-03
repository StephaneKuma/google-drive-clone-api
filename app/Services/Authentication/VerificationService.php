<?php

declare(strict_types=1);

namespace App\Services\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\VerificationContract;
use App\Repositories\Authentication\VerificationRepository;

class VerificationService implements VerificationContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\Authentication\VerificationRepository $repository
     */
    public function __construct(private readonly VerificationRepository $repository)
    {
    }

    /**
     * Verify the email of the user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(FormRequest $request): JsonResponse
    {
        return $this->repository->verify($request);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        return $this->repository->resend($request);
    }
}
