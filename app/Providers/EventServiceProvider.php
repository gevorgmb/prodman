<?php

namespace App\Providers;

use App\Events\AcquisitionCompleteEvent;
use App\Events\ArchiveStockItemEvent;
use App\Listeners\AcquisitionCompleteListener;
use App\Listeners\ArchiveStockItemListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        AcquisitionCompleteEvent::class => [
            AcquisitionCompleteListener::class,
        ],
        ArchiveStockItemEvent::class => [
            ArchiveStockItemListener::class,
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
