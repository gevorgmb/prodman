<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\ContactVerificationRepository;
use App\Repositories\Contracts\ContactVerificationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
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
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
