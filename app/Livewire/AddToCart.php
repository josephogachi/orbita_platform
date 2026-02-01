<?php

namespace App\Livewire;

use App\Models\Product;
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

    public function addToCart()
    {
        // Add the item to the session-based cart
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'quantity' => $this->quantity, // Uses the component's quantity property
            'attributes' => [
                'image' => $this->product->images[0] ?? 'default.jpg',
                'slug'  => $this->product->slug,
            ]
        ]);

        // Notify other components (like the header count) that the cart has changed
        $this->dispatch('cartUpdated');

        // Set a flash message for the next page load
        session()->flash('success', $this->product->name . ' added to your cart!');

        // REDIRECT: This takes the user directly to the cart page to manage quantities
        return redirect()->route('cart.index');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}