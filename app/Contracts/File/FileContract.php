<?php

declare(strict_types=1);

namespace App\Contracts\File;

use App\Http\Requests\File\ParentIdBaseRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface FileContract
{
    /**
     * Get the current user files.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\File|\Baum\Node>|\Illuminate\Pagination\LengthAwarePaginator<\App\Models\File|\Baum\Node>
     */
    public function index(): Collection|LengthAwarePaginator;

    /**
     * Store the uploaded files and folders.
     *
     * @param \App\Http\Requests\File\ParentIdBaseRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParentIdBaseRequest $request): JsonResponse;
}
