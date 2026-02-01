<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Models\ShopSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Force HTTPS logic
        if (config('app.env') !== 'local' || str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        // 2. Shared settings logic (With Safety Wrap)
        try {
            if (Schema::hasTable('shop_settings')) {
                $settings = ShopSetting::first();
                View::share('settings', $settings);
            }
        } catch (\Exception $e) {
            // If the database is missing or migrating, 
            // we catch the error here so the page still loads.
            View::share('settings', null);
        }
    }
}