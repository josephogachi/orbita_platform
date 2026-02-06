<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class ProductController extends Controller
{
    /**
     * Display the product listing.
     */
    public function index(): View
    {
        return view('products.index');
    }

    /**
     * Display the masterpiece product detail page.
     */
    public function show(string $slug): View
    {
        // 1. Get Product with Category relationship for Breadcrumbs/Related
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 2. Get Shop Settings
        $settings = ShopSetting::first();

        /**
         * 3. CALCULATE WHATSAPP NUMBER & MESSAGE
         * Uses 'phone_contact' from your Model/Migration.
         */
        $rawNumber = $settings->phone_contact ?? '254726777733';
        
        // Clean the string (remove +, spaces, dashes) for the URL
        $whatsappNumber = preg_replace('/[^0-9]/', '', $rawNumber);
        
        // Convert local 07... to international 2547...
        if (str_starts_with($whatsappNumber, '0')) {
            $whatsappNumber = '254' . substr($whatsappNumber, 1);
        }

        // Build a masterpiece WhatsApp Message
        $shareUrl = request()->fullUrl();
        $messageText = "Hello Orbita Kenya, I am interested in ordering:\n\n" . 
                       "Product: " . $product->name . "\n" .
                       "Price: KES " . number_format($product->price) . "\n" .
                       "Link: " . $shareUrl;
        
        $whatsappMessage = urlencode($messageText);

        // 4. Related Products (Same category, excluding current)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // 5. Return View with all variables for the masterpiece design
        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'settings' => $settings,
            'whatsappNumber' => $whatsappNumber,
            'whatsappMessage' => $whatsappMessage
        ]);
    }

    /**
     * Handle Add to Cart requests.
     */
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->input('quantity', 1),
            'attributes' => [
                'weight' => $product->weight ?? 1,
                'image'  => is_array($product->images) ? ($product->images[0] ?? null) : $product->images,
                'slug'   => $product->slug
            ]
        ]);

        session()->save();
        
        // Return to cart with success feedback
        return redirect()->route('cart.index')->with('success', $product->name . ' has been added to your cart!');
    }
}