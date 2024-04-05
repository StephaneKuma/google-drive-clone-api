<?php

namespace App\Repositories\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Contracts\File\FileContract;
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

        /** @var \App\Models\User $user */
        $user = auth()->user();

        /** @var array<string, \Illuminate\Http\UploadedFile|array<string, mixed>> $fileTree */
        $fileTree = $request->file_tree;

        if (is_null($parent)) {
            /** @var \App\Models\File $parent */
            $parent = File::query()->userRoot()->first();
        }

        if (!empty($fileTree)) {
            $this->saveFileTree($fileTree, $parent, $user);
        } else {
            /** @var array<int, \Illuminate\Http\UploadedFile> $files */
            $files = $validated['files'];
            foreach ($files as $file) {
                $this->saveFile($file, $user, $parent);
            }
        }

        return response()->json([
            'message' => 'Folder/File(s) uploaded successfully',
        ], 200);
    }

    /**
     * A recursive function to save a file tree structure into the database.
     *
     * @param array<string, mixed> $fileTree The file tree structure to save
     * @param \App\Models\File $parent The parent file object
     * @param \App\Models\User $user The user object associated with the files
     *
     * @return void
     */
    private function saveFileTree(array $fileTree, File $parent, User $user): void
    {
        foreach ($fileTree as $name => $file) {
            if (is_array($file)) {
                /** @var \App\Models\File $folder */
                $folder = $parent->children()->create([
                    'name' => $name,
                    'path' => (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($name),
                    'is_folder' => true,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                ]);

                $this->saveFileTree($file, $folder, $user);
            } else {
                $this->saveFile($file, $user, $parent);
            }
        }
    }

    private function saveFile(UploadedFile $file, User $user, File $parent): void
    {
        $path = $file->store('/files/' . $user->id, 'local');

        $fileName = $file->getClientOriginalName();

        $parent->children()->create([
            'name' => $fileName,
            'path' => (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($fileName),
            'storage_path' => $path,
            'is_folder' => false,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_on_cloud' => false,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
    }
}
