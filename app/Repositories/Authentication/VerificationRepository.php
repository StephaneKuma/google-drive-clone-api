<?php

declare(strict_types=1);

namespace App\Repositories\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\VerificationContract;

class VerificationRepository implements VerificationContract
{
    /**
     * Verify the email of the user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(FormRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => __('Email already verified')]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json(['message' => __('Email successfully verified')]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => __('Email already verified')]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => __('Email verification link sent on your email id')]);
    }
}
