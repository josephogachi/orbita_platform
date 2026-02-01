<?php

namespace App\Livewire;

use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartList extends Component
{
    public function updateQuantity($id, $qty)
    {
        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value' => $qty
            ],
        ]);
        $this->dispatch('cartUpdated');
    }

    public function removeItem($id)
    {
        Cart::remove($id);
        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        Cart::clear();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart-list', [
            'cartItems' => Cart::getContent()->sortBy('id'),
            'total' => Cart::getTotal()
        ]);
    }
}