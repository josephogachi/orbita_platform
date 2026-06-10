<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Category; // Added Category model import
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ShopSetting;
use App\Models\Testimonial;
use App\Models\SideAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        // 1. SAFE SETTINGS CHECK
        // If the table doesn't exist yet, we create a "Fake" object so the view doesn't crash
        if (Schema::hasTable('shop_settings')) {
            $settings = ShopSetting::first() ?? new ShopSetting(['name' => 'Orbita Kenya']);
        } else {
            $settings = new ShopSetting(['name' => 'Orbita Kenya (Setup Required)']);
        }

        // 2. DATA FETCHING (Wrapped in checks to prevent crashes during fresh setup)
        $promotions = Schema::hasTable('promotions') 
            ? Promotion::where('is_active', true)->orderBy('sort_order', 'asc')->get() 
            : collect();

        $sideAds = Schema::hasTable('side_ads') 
            ? SideAd::where('is_active', true)->orderBy('sort_order', 'asc')->get() 
            : collect();

        $clients = Schema::hasTable('clients') 
            ? Client::where('is_visible', true)->orderBy('sort_order', 'asc')->get() 
            : collect();

        $testimonials = Schema::hasTable('testimonials') 
            ? Testimonial::where('is_active', true)->latest()->take(6)->get() 
            : collect();

        // ADDED: Fetch Categories for the new Showcase Section
        $categories = Schema::hasTable('categories') 
            ? Category::all() // If you have an 'is_active' column, change this to Category::where('is_active', true)->get()
            : collect();

        // 3. PRODUCT LOGIC
        if (Schema::hasTable('products')) {
            $baseProductQuery = Product::where('is_active', true)->with('category');
            $newArrivals = (clone $baseProductQuery)->latest()->take(10)->get();
            $hotSelling = (clone $baseProductQuery)->where('is_hot', true)->latest()->take(10)->get();
            $sponsoredProducts = (clone $baseProductQuery)->where('is_sponsored', true)->latest()->take(10)->get();
        } else {
            $newArrivals = $hotSelling = $sponsoredProducts = collect();
        }

        // ADDED: 'categories' to the compact array so the view can access it
        return view('home', compact(
            'settings', 
            'promotions', 
            'sideAds', 
            'clients', 
            'categories', // New variable passed to the view
            'newArrivals',
            'hotSelling',
            'sponsoredProducts',
            'testimonials'
        ));
    }
}