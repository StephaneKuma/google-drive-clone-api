<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Return a new Attribute instance for password with bcrypt set.
     *
     * @return Attribute<never, string>
     */
    public function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value): string => bcrypt($value),
        );
    }

    /**
     * Get all of the files for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\File>
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'created_by', 'id');
    }

    /**
     * Get all of the updatedFiles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\File>
     */
    public function updatedFiles(): HasMany
    {
        return $this->hasMany(File::class, 'updated_by', 'id');
    }

    /**
     * Get all of the fileShared for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\FileShare>
     */
    public function fileShared(): HasMany
    {
        return $this->hasMany(FileShare::class, 'user_id', 'id');
    }

    /**
     * Get all of the starredFile for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\StarredFile>
     */
    public function starredFile(): HasMany
    {
        return $this->hasMany(StarredFile::class, 'user_id', 'id');
    }
}
