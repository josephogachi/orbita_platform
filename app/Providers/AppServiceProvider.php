<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;      // Added for Ngrok support
use App\Models\ShopSetting;

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
    if (str_contains(config('app.url'), 'ngrok-free.dev')) {
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        \Illuminate\Support\Facades\URL::forceScheme('https');
        
        // This line is the magic for mobile styling:
        $this->app['request']->server->set('HTTPS', true);
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('shop_settings')) {
        $settings = \App\Models\ShopSetting::first();
        \Illuminate\Support\Facades\View::share('settings', $settings);
    }
}
}