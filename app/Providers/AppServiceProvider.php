<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import this
use App\Models\ShopSetting;          // Import this
use Illuminate\Support\Facades\Schema; // Import this for safety

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
        // Check if table exists to avoid errors during migration
        if (Schema::hasTable('shop_settings')) {
            $settings = ShopSetting::first();
            // Share the $settings variable with ALL views
            View::share('settings', $settings);
        }
    }
}