<?php

namespace App\Providers;

use App\Events\ProductCreate;
use App\Events\ProductDelete;
use App\Events\ProductUpdate;
use App\Listeners\ProductCreateListener;
use App\Listeners\ProductDeleteListener;
use App\Listeners\ProductUpdateListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductCreate::class => [
            ProductCreateListener::class
        ],
        ProductUpdate::class => [
            ProductUpdateListener::class
        ],
        ProductDelete::class => [
            ProductDeleteListener::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
