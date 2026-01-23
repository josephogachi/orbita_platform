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

        $sideAds = SideAd::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $clients = Client::where('is_visible', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Base query for active products
        $baseProductQuery = Product::where('is_active', true)->with('category');

        // Segmented Collections
        $newArrivals = (clone $baseProductQuery)->latest()->take(10)->get();
        $hotSelling = (clone $baseProductQuery)->where('is_hot', true)->latest()->take(10)->get();
        $sponsoredProducts = (clone $baseProductQuery)->where('is_sponsored', true)->latest()->take(10)->get();

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact(
            'settings', 
            'promotions', 
            'sideAds', 
            'clients', 
            'newArrivals',
            'hotSelling',
            'sponsoredProducts',
            'testimonials'
        ));
    }
}