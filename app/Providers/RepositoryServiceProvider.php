<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\ApartmentCategoryRepository;
use App\Repositories\ApartmentDepartmentRepository;
use App\Repositories\ApartmentProductRepository;
use App\Repositories\ApartmentRepository;
use App\Repositories\ApartmentUserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ContactVerificationRepository;
use App\Repositories\Contracts\ApartmentCategoryRepositoryInterface;
use App\Repositories\Contracts\ApartmentDepartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentProductRepositoryInterface;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ContactVerificationRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\ProductRepository;
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
            ApartmentRepositoryInterface::class,
            ApartmentRepository::class,
        );
        $this->app->bind(
            ApartmentUserRepositoryInterface::class,
            ApartmentUserRepository::class,
        );
        $this->app->bind(
            ContactVerificationRepositoryInterface::class,
            ContactVerificationRepository::class,
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
        $this->app->singleton(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );
        $this->app->singleton(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        $this->app->singleton(
            ApartmentCategoryRepositoryInterface::class,
            ApartmentCategoryRepository::class
        );
        $this->app->singleton(
            ApartmentDepartmentRepositoryInterface::class,
            ApartmentDepartmentRepository::class
        );
        $this->app->singleton(
            ApartmentProductRepositoryInterface::class,
            ApartmentProductRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
