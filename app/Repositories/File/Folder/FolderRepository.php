<?php

declare(strict_types=1);

namespace App\Repositories\File\Folder;

use App\Models\File;
use Illuminate\Support\Str;
use App\Contracts\File\Folder\FolderContract;
use App\Http\Requests\File\ParentIdBaseRequest;

class FolderRepository implements FolderContract
{
    /**
     * Create a new folder based on the validated request data.
     *
     * @param \App\Http\Requests\File\ParentIdBaseRequest $request The form request containing the data for folder creation
     * @return \App\Models\File The newly created folder
     */
    public function store(ParentIdBaseRequest $request): File
    {
        $validated = $request->validated();

        /** @var \App\Models\File|null $parent */
        $parent = $request->parent;

        if (is_null($parent)) {
            /** @var \App\Models\File $parent */
            $parent = File::query()->userRoot()->first();
        }

        $authId = (int) auth()->id();

        /** @var \App\Models\File $folder */
        $folder = $parent->children()->create([
            'name' => $validated['name'],
            'path' => (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($validated['name']),
            'is_folder' => true,
            'created_by' => $authId,
            'updated_by' => $authId,
        ]);

        return $folder;
    }
}
