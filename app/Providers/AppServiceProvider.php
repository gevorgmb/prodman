<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Repositories\Contracts\ApartmentCategoryRepositoryInterface;
use App\Repositories\Contracts\ApartmentDepartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentProductRepositoryInterface;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\ApartmentCategoryService;
use app\Services\ApartmentDepartmentService;
use app\Services\ApartmentProductService;
use App\Services\ApartmentService;
use App\Services\ApartmentUserService;
use App\Services\CategoryService;
use App\Services\Contracts\ApartmentCategoryServiceInterface;
use App\Services\Contracts\ApartmentDepartmentServiceInterface;
use App\Services\Contracts\ApartmentProductServiceInterface;
use App\Services\Contracts\ApartmentServiceInterface;
use App\Services\Contracts\ApartmentUserServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\DepartmentServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\DepartmentService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApartmentServiceInterface::class, function ($app) {
            return new ApartmentService(
                $app->make(ApartmentRepositoryInterface::class),
                $app->make(ApartmentUserRepositoryInterface::class),
            );
        });

        $this->app->singleton(ApartmentUserServiceInterface::class, function ($app) {
            return new ApartmentUserService(
                $app->make(ApartmentRepositoryInterface::class),
                $app->make(ApartmentUserRepositoryInterface::class),
                $app->make(UserRepositoryInterface::class),
            );
        });

        $this->app->singleton(CategoryServiceInterface::class, function ($app) {
            return new CategoryService(
                $app->make(CategoryRepositoryInterface::class),
            );
        });

        $this->app->singleton(DepartmentServiceInterface::class, function ($app) {
            return new DepartmentService(
                $app->make(DepartmentRepositoryInterface::class),
            );
        });

        $this->app->singleton(ProductServiceInterface::class, function ($app) {
            return new ProductService(
                $app->make(ProductRepositoryInterface::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');
        Gate::define('viewPulse', function (User $user) {
            return in_array(request()->ip(), config('settings.trusted_client_ip_list'));
        });
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware('pulse_ip');
        });
    }
}
