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
        //! Authentication
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

        //! Forgot Password
        $this->app->bind(
            \App\Contracts\Authentication\ForgotPasswordContract::class,
            \App\Services\Authentication\ForgotPasswordService::class
        );
        $this->app->bind(
            \App\Services\Authentication\ForgotPasswordService::class,
            fn () => new \App\Services\Authentication\ForgotPasswordService(
                new \App\Repositories\Authentication\ForgotPasswordRepository
            )
        );

        //! Reset Password
        $this->app->bind(
            \App\Contracts\Authentication\ResetPasswordContract::class,
            \App\Services\Authentication\ResetPasswordService::class
        );
        $this->app->bind(
            \App\Services\Authentication\ResetPasswordService::class,
            fn () => new \App\Services\Authentication\ResetPasswordService(
                new \App\Repositories\Authentication\ResetPasswordRepository
            )
        );

        //! Verification
        $this->app->bind(
            \App\Contracts\Authentication\VerificationContract::class,
            \App\Services\Authentication\VerificationService::class
        );
        $this->app->bind(
            \App\Services\Authentication\VerificationService::class,
            fn () => new \App\Services\Authentication\VerificationService(
                new \App\Repositories\Authentication\VerificationRepository
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
