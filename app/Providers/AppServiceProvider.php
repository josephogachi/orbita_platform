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
        // Force HTTPS if the application is not running on localhost
        // This is the "Magic Fix" for mobile styling on Ngrok
        if (config('app.env') !== 'local' || str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        // Shared settings logic
        if (Schema::hasTable('shop_settings')) {
            $settings = ShopSetting::first();
            View::share('settings', $settings);
        }
    }
}