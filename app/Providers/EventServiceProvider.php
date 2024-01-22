<?php

namespace App\Providers;

use App\Events\SendCoupon;
use App\Events\SendDiscount;
use App\Events\SendNewProduct;
use App\Listeners\SendCouponLisrener;
use App\Listeners\SendDiscountListener;
use App\Listeners\SendNewProductListener;
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
        SendDiscount::class => [
            SendDiscountListener::class,
        ],
        SendNewProduct::class => [
            SendNewProductListener::class,
        ],
        SendCoupon::class => [
            SendCouponLisrener::class,
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
