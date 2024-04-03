<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\Authentication\AuthenticationContract::class,
            \App\Services\Authentication\AuthenticationService::class
        );
        $this->app->bind(
            \App\Services\Authentication\AuthenticationService::class,
            fn () => new \App\Services\Authentication\AuthenticationService(
                new \App\Repositories\Authentication\AuthenticationRepository()
            )
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
