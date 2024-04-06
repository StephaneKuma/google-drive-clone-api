<?php

declare(strict_types=1);

namespace App\Actions\File\Folder;

use App\Models\File;
use Illuminate\Support\Str;

class CreateFolder
{
    /**
     * Invoke the class instance.
     *
     * @param  \App\Models\File  $parent The parent folder
     * @param  string  $name The name of the folder to be created
     *
     * @return \App\Models\File
     */
    public function __invoke(File $parent, string $name): File
    {
        $authId = (int) auth()->id();

        /** @var \App\Models\File $folder */
        $folder = $parent->children()->create([
            'name' => $name,
            'path' => (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($name),
            'is_folder' => true,
            'created_by' => $authId,
            'updated_by' => $authId,
        ]);

        return $folder;
    }
}
