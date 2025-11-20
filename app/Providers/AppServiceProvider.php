<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Models\Facility;
use App\Observers\BookingObserver;
use App\Observers\FacilityObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Booking::observe(BookingObserver::class);
        Facility::observe(FacilityObserver::class);
    }
}
