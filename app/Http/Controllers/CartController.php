<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    /**
     * Display the shopping cart page.
     */
    public function index()
    {
        // Fetch all items currently in the user's session cart
        $cartItems = Cart::getContent();
        
        return view('cart.index', compact('cartItems'));
    }
}