<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\ApartmentService;
use App\Services\ApartmentUserService;
use App\Services\Contracts\ApartmentServiceInterface;
use App\Services\Contracts\ApartmentUserServiceInterface;
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
