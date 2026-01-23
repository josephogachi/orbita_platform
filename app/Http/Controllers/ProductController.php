<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Find product by slug or fail with a 404 error
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch related products (same category) for the "You may also like" section
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}