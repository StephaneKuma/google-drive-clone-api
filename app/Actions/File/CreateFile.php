<?php

declare(strict_types=1);

namespace App\Actions\File;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class CreateFile
{
    /**
     * Invoke the class instance.
     *
     * @param  \App\Models\File  $parent The parent folder
     * @param  \Illuminate\Http\UploadedFile  $file The file to be uploaded
     *
     * @return void
     */
    public function __invoke(File $parent, UploadedFile $file): void
    {
        $authId = (int) auth()->id();

        $path = $file->store('/files/' . $authId, 'local');

        $fileName = $file->getClientOriginalName();

        $parent->children()->create([
            'name' => $fileName,
            'path' => (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($fileName),
            'storage_path' => $path,
            'is_folder' => false,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_on_cloud' => false,
            'created_by' => $authId,
            'updated_by' => $authId
        ]);
    }
}
