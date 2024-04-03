<?php

namespace App\Http\Requests\Authentication\ResetPassword;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * Retrieves the body parameters for the function.
     *
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'token' => [
                'description' => 'The reset password token.',
                'exemple' => '1234569853235'
            ],
            'email' => [
                'description' => 'The email address.',
                'exemple' => 'john.doe@example.com'
            ],
            'password' => [
                'description' => 'The new password.',
                'exemple' => 'new password'
            ],
            'password_confirmation' => [
                'description' => 'The password confirmation.',
                'exemple' => 'new password'
            ]
        ];
    }
}
