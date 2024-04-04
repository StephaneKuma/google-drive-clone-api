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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
