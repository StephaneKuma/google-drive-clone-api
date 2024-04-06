<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FileProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //! Folder
        $this->app->bind(
            \App\Contracts\File\Folder\FolderContract::class,
            \App\Services\File\Folder\FolderService::class
        );
        $this->app->bind(
            \App\Services\File\Folder\FolderService::class,
            fn () => new \App\Services\File\Folder\FolderService(
                new \App\Repositories\File\Folder\FolderRepository()
            )
        );

        //! File
        $this->app->bind(
            \App\Contracts\File\FileContract::class,
            \App\Services\File\FileService::class
        );
        $this->app->bind(
            \App\Services\File\FileService::class,
            fn () => new \App\Services\File\FileService(
                new \App\Repositories\File\FileRepository()
            )
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
