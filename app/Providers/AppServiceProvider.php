<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

// --- Import Models ---
use App\Models\User;
use App\Models\ShopSetting;
use App\Models\ShippingZone;
use App\Models\HeroSlide;
use App\Models\Subscriber;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Project;
use App\Models\ProjectLead; // New: CRM Model

// --- Import Policies ---
use App\Policies\UserPolicy;
use App\Policies\ShopSettingPolicy;
use App\Policies\ShippingZonePolicy;
use App\Policies\HeroSlidePolicy;
use App\Policies\SubscriberPolicy;
use App\Policies\PromotionPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\BrandPolicy;
use App\Policies\ProductPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\ProjectPolicy;

// --- Import Observers ---
use App\Observers\ProjectLeadObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // =================================================================
        // 1. STRICT ACCESS CONTROL (Hides Menu Items for Sales Team)
        // =================================================================
        
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ShopSetting::class, ShopSettingPolicy::class);
        Gate::policy(ShippingZone::class, ShippingZonePolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(HeroSlide::class, HeroSlidePolicy::class);
        Gate::policy(Testimonial::class, TestimonialPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Subscriber::class, SubscriberPolicy::class);
        Gate::policy(Promotion::class, PromotionPolicy::class);

        // =================================================================
        // 2. MODEL OBSERVERS (Automation & Notifications)
        // =================================================================
        
        // This triggers the email notification when a lead status changes to 'completed'
        ProjectLead::observe(ProjectLeadObserver::class);


        // =================================================================
        // 3. HTTPS FORCE LOGIC (For Ngrok & Production)
        // =================================================================
        if (app()->environment('production') || str_contains(request()->header('host'), 'ngrok')) {
            URL::forceScheme('https');
        }


        // =================================================================
        // 4. GLOBAL VIEW SETTINGS (WhatsApp & Footer Info)
        // =================================================================
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