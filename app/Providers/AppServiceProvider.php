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
    // 1. Improved Force HTTPS logic for ngrok and production
    if (app()->environment('production') || str_contains(request()->header('host'), 'ngrok')) {
        URL::forceScheme('https');
    }

    // 2. Shared settings logic (Your existing code is solid)
    try {
        if (Schema::hasTable('shop_settings')) {
            $settings = ShopSetting::first();
            View::share('settings', $settings);

            $rawPhone = $settings->phone_contact ?? '254726777733';
            $cleanWhatsApp = preg_replace('/[^0-9]/', '', $rawPhone);
            
            if (str_starts_with($cleanWhatsApp, '0')) {
                $cleanWhatsApp = '254' . substr($cleanWhatsApp, 1);
            }

            View::share('whatsappNumber', $cleanWhatsApp);
            View::share('whatsappMessage', 'Hello, I am interested in your products.');
        }
    } catch (\Exception $e) {
        View::share('settings', null);
        View::share('whatsappNumber', '254726777733');
    }
}
}