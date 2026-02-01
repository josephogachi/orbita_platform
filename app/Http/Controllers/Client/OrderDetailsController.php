<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
    public function show($order_number)
    {
        // Find the order belonging to the logged-in user
        $order = Order::where('order_number', $order_number)
            ->where('user_id', auth()->id()) // Security check: Only owner can view
            ->with('items') // Load the items inside the order
            ->firstOrFail();

        return view('client.orders.show', compact('order'));
    }
}