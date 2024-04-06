<?php

declare(strict_types=1);

namespace App\Services\File\Folder;

use App\Models\File;
use App\Contracts\File\Folder\FolderContract;
use App\Http\Requests\File\ParentIdBaseRequest;
use App\Repositories\File\Folder\FolderRepository;

class FolderService implements FolderContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\File\Folder\FolderRepository $repository
     */
    public function __construct(private readonly FolderRepository $repository)
    {
    }

    /**
     * Create a new folder based on the validated request data.
     *
     * @param \App\Http\Requests\File\ParentIdBaseRequest $request The form request containing the data for folder creation
     * @return \App\Models\File The newly created folder
     */
    public function store(ParentIdBaseRequest $request): File
    {
        return $this->repository->store($request);
    }
}
