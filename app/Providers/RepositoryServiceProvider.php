<?php

declare(strict_types=1);

namespace App\Providers;

use app\Repositories\Contracts\ContactVerificationRepositoryInterface;
use app\Repositories\ContactVerificationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ContactVerificationRepositoryInterface::class,
            ContactVerificationRepository::class,
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
