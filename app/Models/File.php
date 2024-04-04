<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'storage_path',
        'parent_id',
        'lft',
        'rgt',
        'depth',
        'is_folder',
        'mime',
        'size',
        'uploaded_on_cloud',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_folder' => 'boolean',
            'uploaded_on_cloud' => 'boolean',
        ];
    }

    /**
     * Get the creator that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\File>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the editor that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\File>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all of the shared for the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\FileShare>
     */
    public function shared(): HasMany
    {
        return $this->hasMany(FileShare::class, 'file_id', 'id');
    }

    /**
     * Get all of the starred for the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\StarredFile>
     */
    public function starred(): HasMany
    {
        return $this->hasMany(StarredFile::class, 'file_id', 'id');
    }
}
