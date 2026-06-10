<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        // Fetch only active products to ensure we don't send Google to dead pages
        $products = Product::where('is_active', true)->orderBy('updated_at', 'desc')->get();

        // Return the view, but strictly tell the browser/Google it is an XML file
        return response()->view('sitemap', compact('products'))
                         ->header('Content-Type', 'text/xml');
    }
}