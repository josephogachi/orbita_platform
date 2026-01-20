<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ShopSetting;
use App\Models\Testimonial;
use App\Models\SideAd;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = ShopSetting::first();

        $promotions = Promotion::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // UPDATED: Fetch ALL active ads (get instead of first)
        $sideAds = SideAd::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $clients = Client::where('is_visible', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(20) 
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact(
            'settings', 
            'promotions', 
            'sideAds', // <--- PLURAL variable
            'clients', 
            'featuredProducts', 
            'testimonials'
        ));
    }
}