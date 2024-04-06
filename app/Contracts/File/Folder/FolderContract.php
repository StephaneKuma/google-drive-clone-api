<?php

declare(strict_types=1);

namespace App\Contracts\File\Folder;

use App\Models\File;
use App\Http\Requests\File\ParentIdBaseRequest;

interface FolderContract
{
    /**
     * Create a new folder based on the validated request data.
     *
     * @param \App\Http\Requests\File\ParentIdBaseRequest $request The form request containing the data for folder creation
     * @return \App\Models\File The newly created folder
     */
    public function store(ParentIdBaseRequest $request): File;
}
