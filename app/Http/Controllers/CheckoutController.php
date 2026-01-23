<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Intasend\IntasendPHP\Checkout;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('products.index');
        }

        return view('checkout.index', [
            'cartItems' => Cart::getContent(),
            'total' => Cart::getTotal()
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate customer info
        $request->validate([
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required'
        ]);

        $cartItems = Cart::getContent();
        $grandTotal = Cart::getTotal();

        // 2. Create the Order
        $order = Order::create([
            'order_number' => 'ORB-' . strtoupper(str()->random(8)),
            'user_id' => auth()->id(),
            'status' => 'new',
            'payment_status' => 'unpaid',
            'currency' => 'KES',
            'shipping_address' => $request->address,
            'grand_total' => $grandTotal,
            'notes' => $request->notes,
        ]);

        // 3. Save Order Items from the Cart
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'unit_amount' => $item->price,
                'total_amount' => $item->getPriceSum(),
            ]);
        }

        // 4. Prepare IntaSend Checkout
        $credentials = [
            'publishable_key' => config('services.intasend.key'),
            'token' => config('services.intasend.secret'),
            'test' => config('services.intasend.test_mode'),
        ];

        $checkout = new Checkout();
        $checkout->init($credentials);

        try {
            $payment = $checkout->create(
                $amount = $order->grand_total,
                $currency = "KES",
                $customer_link = null,
                $host = url('/'),
                $redirect_url = route('payment.status'), // Using your existing status route
                $api_ref = $order->order_number,
                $comment = "Payment for Orbita Order " . $order->order_number
            );

            // 5. Clear the Cart after successful payment initialization
            Cart::clear();

            return redirect($payment->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }
}