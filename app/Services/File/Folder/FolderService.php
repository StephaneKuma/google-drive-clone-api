<?php

declare(strict_types=1);

namespace App\Services\File\Folder;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\File\Folder\FolderContract;
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
     * @param \Illuminate\Foundation\Http\FormRequest $request The form request containing the data for folder creation
     * @return \App\Models\File The newly created folder
     */
    public function store(FormRequest $request): File
    {
        return $this->repository->store($request);
    }
}
