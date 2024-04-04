<?php

namespace App\Http\Requests\File\Folder;

use Illuminate\Validation\Rule;
use App\Http\Requests\File\ParentIdBaseRequest;
use App\Models\File;

class StoreRequest extends ParentIdBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'name' => [
                    'required',
                    'string',
                    Rule::unique(File::class, 'name')
                        ->where('created_by', (int) auth()->id())
                        ->where('parent_id', $this->parent_id),
                    'max:1024'
                ],
            ]
        );
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'The folder :input already exists.',
        ];
    }

    /**
     * Retrieves the body parameters for the function.
     *
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return array_merge(
            parent::bodyParameters(),
            [
                'name' => [
                    'description' => 'The name of the folder.',
                    'exemples' => 'My Folder',
                ],
            ]
        );
    }
}
