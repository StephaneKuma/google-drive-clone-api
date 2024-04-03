<?php

declare(strict_types=1);

namespace App\Repositories\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\ForgotPasswordContract;
use Illuminate\Support\Facades\Password;

class ForgotPasswordRepository implements ForgotPasswordContract
{
    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(FormRequest $request): JsonResponse
    {
        /** @var array<int, string> $validated */
        $validated = $request->validated();

        $status = Password::sendResetLink(
            $validated['email']
        );

        return $status === Password::RESET_LINK_SENT ?
            response()->json(['message' => __($status)]) :
            response()->json(
                ['error' => __($status)],
                JsonResponse::HTTP_BAD_REQUEST
            );
    }
}
