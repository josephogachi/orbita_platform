<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ShopSetting;
use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class AddToCart extends Component
{
    public $product;
    public $quantity = 1;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Ensure quantity never drops below 1
     */
    public function updatedQuantity($value)
    {
        if ($value < 1) {
            $this->quantity = 1;
        }
    }

    public function addToCart()
    {
        // 1. Add the item to the session-based cart
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'quantity' => (int) $this->quantity, 
            'attributes' => [
                'image' => is_array($this->product->images) ? ($this->product->images[0] ?? 'default.jpg') : $this->product->images,
                'slug'  => $this->product->slug,
                'weight' => $this->product->weight ?? 1, 
            ]
        ]);

        // 2. Persist session for stability
        session()->save();

        // 3. Notify Navbar
        $this->dispatch('cartUpdated');

        // 4. Set flash message
        session()->flash('success', $this->product->name . ' added to cart!');

        // 5. Redirect to cart
        return redirect()->route('cart.index');
    }

    public function render()
    {
        // Fetch dynamic settings for the WhatsApp number
        $settings = ShopSetting::first();
        $rawNumber = $settings->phone_contact ?? '254726777733';
        $whatsappNumber = preg_replace('/[^0-9]/', '', $rawNumber);
        
        // International format fix
        if (str_starts_with($whatsappNumber, '0')) {
            $whatsappNumber = '254' . substr($whatsappNumber, 1);
        }

        // Masterpiece Message: Includes Name, Price, and Link
        $message = "Hello Orbita Kenya! I'm interested in:\n\n" . 
                   "*Item:* " . $this->product->name . "\n" . 
                   "*Price:* KES " . number_format($this->product->price) . "\n\n" . 
                   "Link: " . route('product.show', $this->product->slug);
        
        $whatsappMessage = urlencode($message);

        return view('livewire.add-to-cart', [
            'whatsappNumber' => $whatsappNumber,
            'whatsappMessage' => $whatsappMessage
        ]);
    }
}