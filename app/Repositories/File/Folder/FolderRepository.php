<?php

declare(strict_types=1);

namespace App\Repositories\File\Folder;

use App\Actions\File\Folder\CreateFolder;
use App\Models\File;
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

        /** @var \App\Models\File $folder */
        $folder = app(CreateFolder::class)($parent, $validated['name']);

        return $folder;
    }
}
