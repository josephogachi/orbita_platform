<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ShopSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the creative homepage.
     */
    public function index()
    {
        // 1. Fetch Global Settings (Countdown, Logo, Contact Info)
        $settings = ShopSetting::first();

        // 2. Fetch Hero Banners (Active & Sorted)
        $promotions = Promotion::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 3. Fetch Client Logos (Active & Sorted)
        $clients = Client::where('is_visible', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 4. Fetch Products for the Creative Grid
        // We fetch the latest 20 active products and eager-load the category
        // so the frontend tabs (Hot/New/Sponsored) have plenty of data to work with.
        $featuredProducts = Product::where('is_active', true)
            ->with('category') // Optimizes performance (prevents N+1 query issue)
            ->latest()
            ->take(20) 
            ->get();

        // 5. Fetch Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact(
            'settings', 
            'promotions', 
            'clients', 
            'featuredProducts', 
            'testimonials'
        ));
    }
}