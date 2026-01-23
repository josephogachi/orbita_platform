<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Intasend\IntasendPHP\Checkout;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate customer info
        $request->validate([
            'phone' => 'required', // Essential for M-Pesa
            'email' => 'required|email',
            'address' => 'required'
        ]);

        // 2. Create the Order (Using your existing schema)
        $order = Order::create([
            'order_number' => 'ORB-' . strtoupper(str()->random(8)),
            'user_id' => auth()->id(),
            'status' => 'new',
            'payment_status' => 'unpaid',
            'currency' => 'KES',
            'shipping_address' => $request->address,
            'grand_total' => $request->total, // Total from cart
            'notes' => $request->notes,
        ]);

        // 3. Save Order Items (Assuming you have cart data in session)
        // foreach($cartItems as $item) { ... OrderItem::create(...) ... }

        // 4. Send to Payment Gateway (M-Pesa / Card)
        $credentials = [
            'publishable_key' => config('services.intasend.key'),
            'token' => config('services.intasend.secret'),
            'test' => config('services.intasend.test_mode'),
        ];

        $checkout = new Checkout();
        $checkout->init($credentials);

        $payment = $checkout->create(
            $amount = $order->grand_total,
            $currency = "KES",
            $customer_link = null,
            $host = url('/'),
            $redirect_url = route('checkout.success'),
            $api_ref = $order->order_number, // Link gateway to your Order Number
            $comment = "Payment for Orbita Order " . $order->order_number
        );

        return redirect($payment->url);
    }
}