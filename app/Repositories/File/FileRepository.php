<?php

namespace App\Repositories\File;

use App\Actions\File\CreateFile;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Contracts\File\FileContract;
use App\Actions\File\Folder\CreateFolder;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\File\ParentIdBaseRequest;
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

    /**
     * Store the uploaded files and folders.
     *
     * @param \App\Http\Requests\File\ParentIdBaseRequest $request description
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParentIdBaseRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $parent = $request->parent;

        /** @var array<string, \Illuminate\Http\UploadedFile|array<string, mixed>> $fileTree */
        $fileTree = $request->file_tree;

        if (is_null($parent)) {
            /** @var \App\Models\File $parent */
            $parent = File::query()->userRoot()->first();
        }

        if (!empty($fileTree)) {
            $this->saveFileTree($parent, $fileTree);
        } else {
            /** @var array<int, \Illuminate\Http\UploadedFile> $files */
            $files = $validated['files'];
            foreach ($files as $file) {
                $this->saveFile($parent, $file);
            }
        }

        return response()->json([
            'message' => 'Folder/File(s) uploaded successfully',
        ], 200);
    }

    /**
     * A recursive function to save a file tree structure into the database.
     *
     * @param \App\Models\File $parent The parent file object
     * @param array<string, mixed> $fileTree The file tree structure to save
     *
     * @return void
     */
    private function saveFileTree(File $parent, array $fileTree): void
    {
        foreach ($fileTree as $name => $file) {
            if (is_array($file)) {
                /** @var \App\Models\File $folder */
                $folder = app(CreateFolder::class)($parent, $name);

                $this->saveFileTree($folder, $file);
            } else {
                $this->saveFile($parent, $file);
            }
        }
    }

    /**
     * Save a file under a parent directory.
     *
     * @param \App\Models\File $parent The parent directory where the file will be saved
     * @param \Illuminate\Http\UploadedFile $file The file to be saved
     *
     * @return void
     */
    private function saveFile(File $parent, UploadedFile $file): void
    {
        app(CreateFile::class)($parent, $file);
    }
}
