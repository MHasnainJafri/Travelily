<?php

namespace App\Providers;

use App\Models\Jam;
use App\Models\JamUser;
use App\Observers\JamObserver;
use App\Observers\JamUserObserver;
use Illuminate\Support\ServiceProvider;

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
        Jam::observe(JamObserver::class);
        JamUser::observe(JamUserObserver::class);
    }
}
