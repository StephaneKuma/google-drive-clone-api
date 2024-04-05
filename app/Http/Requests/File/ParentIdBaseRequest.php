<?php

declare(strict_types=1);

namespace App\Http\Requests\File;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ParentIdBaseRequest extends FormRequest
{
    public ?File $parent = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->parent = File::query()->where('id', $this->input('parent_id'))->first();

        if ($this->parent && !$this->parent->isOwnedBy((int) auth()->id())) {
            return false;
        }

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
            'parent_id' => [
                'required',
                Rule::exists(File::class, 'id')
                    ->where(function (Builder $query) {
                        return $query->where('is_folder', true)
                            ->where(
                                'created_by',
                                (int) auth()->id()
                            );
                    }),
            ],
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
            'parent_id' => [
                'description' => 'The ID of the parent folder.',
                'example' => '1',
            ]
        ];
    }
}
