<?php

namespace App\Providers;

use App\Events\AcquisitionCompleteEvent;
use App\Listeners\AcquisitionCompleteListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        AcquisitionCompleteEvent::class => [
            AcquisitionCompleteListener::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
