<?php

declare(strict_types=1);

namespace App\Services\File;

use App\Contracts\File\FileContract;
use App\Http\Requests\File\ParentIdBaseRequest;
use App\Repositories\File\FileRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class FileService implements FileContract
{
    /**
     * Create a new class instance.
     *
     * @param \App\Repositories\File\FileRepository $repository
     */
    public function __construct(private readonly FileRepository $repository)
    {
    }

    /**
     * Get the current user files.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\File|\Baum\Node>|\Illuminate\Pagination\LengthAwarePaginator<\App\Models\File|\Baum\Node>
     */
    public function index(): Collection|LengthAwarePaginator
    {
        return $this->repository->index();
    }

    /**
     * Store the uploaded files and folders.
     *
     * @param \App\Http\Requests\File\StoreRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParentIdBaseRequest $request): JsonResponse
    {
        return $this->repository->store($request);
    }
}
