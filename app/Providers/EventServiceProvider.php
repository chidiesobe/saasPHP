<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ImageUpdated;
use App\Listeners\DeleteOldImage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event→listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ImageUpdated::class => [
            DeleteOldImage::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
