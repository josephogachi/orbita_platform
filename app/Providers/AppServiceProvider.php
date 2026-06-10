<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
use App\Models\ProjectLead; 
use App\Models\Order;
use App\Models\Campaign;
use App\Models\Lead;

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
use App\Policies\CampaignPolicy;
use App\Policies\LeadPolicy;

// --- Import Observers ---
use App\Observers\ProjectLeadObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Left intentionally blank. The DOMPDF fix belongs in bootstrap/app.php
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
        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::policy(Lead::class, LeadPolicy::class);

        // =================================================================
        // 2. MODEL OBSERVERS & SYSTEM NOTIFICATIONS
        // =================================================================
        
        // Lead Status Automation
        ProjectLead::observe(ProjectLeadObserver::class);

        // 🚀 2a. BRANDED ADMIN ALERT: New Orders
        Order::created(function ($order) {
            try {
                Mail::send('emails.admin-order-alert', ['order' => $order], function ($message) use ($order) {
                    $message->to('info@orbitakenya.com')
                            ->subject('🚀 Order Alert: #' . $order->id . ' - ' . ($order->user->name ?? 'Guest'));
                });
            } catch (\Exception $e) {
                Log::error("Order Admin Email Failed: " . $e->getMessage());
            }
        });

        // ✨ 2b. BRANDED ADMIN ALERT: New User Registration
        User::created(function ($user) {
            try {
                Mail::send('emails.admin-user-alert', ['user' => $user], function ($message) use ($user) {
                    $message->to('info@orbitakenya.com')
                            ->subject('👤 New Account: ' . $user->name);
                });
            } catch (\Exception $e) {
                Log::error("User Admin Email Failed: " . $e->getMessage());
            }
        });

        // =================================================================
        // 3. HTTPS FORCE LOGIC (Fixed to prevent Artisan crashes)
        // =================================================================
        if (!app()->runningInConsole()) {
            $host = (string) request()->header('host');
            if (app()->environment('production') || str_contains($host, 'ngrok')) {
                URL::forceScheme('https');
            }
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
            View::share('whatsappMessage', 'Hello, I am interested in your products.'); // Fixed: Added missing fallback
        }
    }
}