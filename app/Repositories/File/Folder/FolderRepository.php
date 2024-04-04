<?php

declare(strict_types=1);

namespace App\Repositories\File\Folder;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\File\Folder\FolderContract;

class FolderRepository implements FolderContract
{
    /**
     * Create a new folder based on the validated request data.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request The form request containing the data for folder creation
     * @return \App\Models\File The newly created folder
     */
    public function store(FormRequest $request): File
    {
        $validated = $request->validated();

        /** @var \App\Models\File|null $parent */
        $parent = $request->parent ?? null;

        if (is_null($parent)) {
            /** @var \App\Models\File $parent */
            $parent = File::query()->userRoot()->first();
        }

        $authId = (int) auth()->id();

        /** @var \App\Models\File $folder */
        $folder = $parent->children()->create([
            'name' => $validated['name'],
            'is_folder' => true,
            'created_by' => $authId,
            'updated_by' => $authId,
        ]);

        return $folder;
    }
}
