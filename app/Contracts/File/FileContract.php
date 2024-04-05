<?php

declare(strict_types=1);

namespace App\Contracts\File;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface FileContract
{
    /**
     * Get the current user files.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\File|\Baum\Node>|\Illuminate\Pagination\LengthAwarePaginator<\App\Models\File|\Baum\Node>
     */
    public function index(): Collection|LengthAwarePaginator;
}
