<?php

declare(strict_types=1);

namespace App\Http\Controllers\File;

use App\Contracts\File\FileContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\StoreRequest;
use App\Http\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Files Management
 *
 * APIs for managing users files
 *
 * @authenticated
 */
class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\File\FileContract $service
     */
    public function __construct(private readonly FileContract $service)
    {
    }

    /**
     * User files
     *
     * Get the current user files.
     *
     * @header Accept-Language en
     *
     * @urlParam folder string  The path of the folder. Example: documents
     * @urlParam perPage int The number of files per page. Example: 15
     * @urlParam page int The page number. Example: 1
     *
     * @apiResourceCollection \App\Http\Resources\FileResource
     * @apiResourceModel \App\Models\File
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(): JsonResource
    {
        $files = $this->service->index();

        return FileResource::collection($files);
    }

    /**
     * Upload files
     *
     * Store the uploaded files and folders.
     *
     * @header Accept-Language en
     *
     * @response 200 {"message":"'Folder/File(s) uploaded successfully'"}
     *
     * @param \App\Http\Requests\File\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return $this->service->store($request);
    }
}
