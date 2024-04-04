<?php

declare(strict_types=1);

namespace App\Models;

use Baum\Node;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $owner
 */
class File extends Node
{
    use HasFactory;

    protected static function booting()
    {
        if (auth()->check()) {
            static::creating(function (self $file) {
                $file->created_by = (int) auth()->id();
                $file->updated_by = (int) auth()->id();

                if (!$file->parent) {
                    return;
                }

                /** @var File $parent */
                $parent = $file->parent;

                $file->path = (!$parent->isRoot() ? $parent->path . '/' : '') . Str::slug($file->name);
            });

            static::updating(function (self $file) {
                $file->updated_by = (int) auth()->id();
            });
        }
    }

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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'owner'
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
     * Retrieve the owner attribute based on the creator of the item.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    public function owner(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): string {
                return $attributes['created_by'] == (int) auth()->id() ? 'me' : $this->creator->name;
            }
        );
    }

    /**
     * Check if the item is owned by the specified user ID.
     *
     * @param int $userId The user ID to check against
     * @return bool
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->created_by === $userId;
    }

    /**
     * Get the root node
     *
     * @param \Illuminate\Database\Eloquent\Builder<\App\Models\File> $query description
     */
    public function scopeUserRoot(Builder $query): void
    {
        $query->where('created_by', (int) auth()->id())
            ->where('parent_id', null);
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
     * Get the updater that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\File>
     */
    public function updater(): BelongsTo
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
