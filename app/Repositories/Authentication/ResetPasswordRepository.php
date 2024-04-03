<?php

namespace App\Repositories\Authentication;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Authentication\ResetPasswordContract;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ResetPasswordRepository implements ResetPasswordContract
{
    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(FormRequest $request): JsonResponse
    {
        /** @var array<int, string> $validated */
        $validated = $request->validated();

        $status = Password::reset($validated, function (User $user, string $password) {
            $user->forceFill([
                'password' => $password,
            ])->save();
        });

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], JsonResponse::HTTP_BAD_REQUEST);
    }
}
