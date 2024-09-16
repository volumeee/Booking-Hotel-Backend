<?php

namespace App\Providers;

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
    public function boot()
    {
        if (env('MAX_EXECUTION_TIME')) {
            set_time_limit((int) env('MAX_EXECUTION_TIME'));
        }
    }

}
