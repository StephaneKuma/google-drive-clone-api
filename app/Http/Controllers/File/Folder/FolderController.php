<?php

declare(strict_types=1);

namespace App\Http\Controllers\File\Folder;

use App\Contracts\File\Folder\FolderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\Folder\StoreRequest;
use App\Http\Resources\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Files Management
 *
 * APIs for managing users files
 *
 * @subgroup Folder Management
 * @subgroupDescription APIs for managing users folders
 *
 * @authenticated
 */
class FolderController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @param \App\Contracts\File\Folder\FolderContract $service
     */
    public function __construct(private readonly FolderContract $service)
    {
    }

    /**
     * Create folder
     *
     * Store a newly created folder.
     *
     * @header Accept-Language en
     *
     * @apiResource App\Http\Resources\FileResource
     * @apiResourceModel App\Models\File
     *
     * @param \App\Http\Requests\File\Folder\StoreRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreRequest $request): JsonResource
    {
        $folder = $this->service->store($request);

        return FileResource::make($folder);
    }
}
