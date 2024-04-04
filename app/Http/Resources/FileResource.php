<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\File
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'storage_path' => $this->storage_path,
            'parent_id' => $this->parent_id,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'depth' => $this->depth,
            'is_folder' => $this->is_folder,
            'mime' => $this->mime,
            'size' => $this->size,
            'uploaded_on_cloud' => $this->uploaded_on_cloud,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'updated_by' => new UserResource($this->whenLoaded('updater')),
            'created_at' => new DateTimeResource($this->created_at),
        ];
    }
}
