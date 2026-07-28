<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Identity\IdentityVerificationService;
use App\Services\Identity\MockIdentityVerificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
    IdentityVerificationService::class,
    MockIdentityVerificationService::class
);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
