<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the product gallery.
     */
    public function index(): View
    {
        // This returns the view that contains the Livewire product-list component
        return view('products.index');
    }

    /**
     * Display a single product detail page.
     * * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        // Find product by slug or fail with a 404 error
        // Ensuring is_active prevents people from accessing hidden products via direct link
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch related products (same category) for the "You may also like" section
        // We exclude the current product so it doesn't recommend itself
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}