<?php

namespace App\Repositories\File;

use App\Models\File;
use App\Contracts\File\FileContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FileRepository implements FileContract
{
    /**
     * Get the current user files.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\File|\Baum\Node>|\Illuminate\Pagination\LengthAwarePaginator<\App\Models\File|\Baum\Node>
     */
    public function index(): Collection|LengthAwarePaginator
    {
        /** @var string|null $folder */
        $folder = request('folder', null);

        if (!is_null($folder) && is_string($folder)) {
            /** @var \App\Models\File $folder */
            $folder = File::query()->where('created_by', (int) auth()->id())
                ->where('path', $folder)
                ->firstOrFail();
        }

        if (is_null($folder)) {
            /** @var \App\Models\File $folder */
            $folder = File::query()->userRoot()->first();
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\File|\Baum\Node> $ancestors */
        $ancestors = $folder->ancestorsAndSelf()->get();

        /** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\File|\Baum\Node> $files */
        $files = $folder->descendantsAndSelf()
            ->where('created_by', (int) auth()->id())
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 15));

        return $files;
    }
}
