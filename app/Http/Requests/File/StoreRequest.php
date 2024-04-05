<?php

namespace App\Http\Requests\File;

use App\Models\File;

class StoreRequest extends ParentIdBaseRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        /** @var array<int, string> $paths */
        $paths = array_filter($this->relative_paths ?? [], fn ($element) => $element != null);

        $this->merge([
            'file_paths' => $paths,
            'folder_name' => $this->detectFolderName($paths)
        ]);
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $validated = $this->validated();

        $this->replace([
            'file_tree' => $this->buildFileTree($this->file_paths, $validated['files'])
        ]);
    }

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
                'files.*' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) {
                        if (!$this->folder_name) {
                            /** @var \Illuminate\Http\UploadedFile $value */
                            $exists = File::query()->where('name', $value->getClientOriginalName())
                                ->where('created_by', (int) auth()->id())
                                ->where('parent_id', $this->parent_id)
                                ->exists();

                            if ($exists) {
                                $fail('The file "' . $value->getClientOriginalName() . '" already exists.');
                            }
                        }
                    }
                ],
                'folder_name' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            /** @var string $value */
                            $file = File::query()->where('name', $value)
                                ->where('created_by', (int) auth()->id())
                                ->where('parent_id', $this->parent_id)
                                ->whereNull('deleted_at')
                                ->exists();

                            if ($file) {
                                $fail('Folder "' . $value . '" already exists.');
                            }
                        }
                    }
                ]
            ]
        );
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
                'files.*' => [
                    'description' => 'The file to upload.',
                    'example' => 'image.png',
                ]
            ]
        );
    }

    /**
     * Detects the folder name from the given array of paths.
     *
     * @param array<int, string> $paths The array of paths to be processed.
     * @return string|null The folder name from the first path, or null if the input array is empty.
     */
    public function detectFolderName(array $paths): ?string
    {
        if (!$paths) {
            return null;
        }

        $parts = explode("/", $paths[0]);

        return $parts[0];
    }

    /**
     * Builds a file tree based on the given paths and files.
     *
     * @param array<int, string> $paths The array of paths
     * @param array<int, \Illuminate\Http\UploadedFile> $files The array of files
     * @return array<string, mixed> The built file tree
     */
    private function buildFileTree(array $paths, array $files): array
    {
        $paths = array_slice($paths, 0, count($files));
        $paths = array_filter($paths, fn ($element) => $element != null);

        $tree = [];

        foreach ($paths as $key => $path) {
            $parts = explode("/", $path);

            $currentNode = &$tree;

            foreach ($parts as $k => $part) {
                if (!isset($currentNode[$part])) {
                    $currentNode[$part] = [];
                }

                if ($k === count($parts) - 1) {
                    $currentNode[$part] = $files[$key];
                } else {
                    $currentNode = &$currentNode[$part];
                }
            }
        }

        return $tree;
    }
}
