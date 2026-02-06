<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Services\MpesaService; // Assuming you have a service for Daraja logic

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('products.index');
        }

        $cartItems = Cart::getContent();
        
        // Calculate Total Weight
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->attributes->weight ?? 1.0) * $item->quantity;
        });

        // Shipping: KES 100 per KG
        $shippingCost = $totalWeight * 100; 
        $subtotal = Cart::getTotal();
        $grandTotal = $subtotal + $shippingCost;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'grandTotal', 'totalWeight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone'   => 'required',
            'address' => 'required',
        ]);

        // 1. Standardize Phone for M-Pesa (Format: 2547xxxxxxxx)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }

        // 2. Calculate Totals Securely
        $totalWeight = Cart::getContent()->sum(fn($item) => ($item->attributes->weight ?? 1.0) * $item->quantity);
        $shippingCost = $totalWeight * 100;
        $grandTotal = (int)(Cart::getTotal() + $shippingCost); // M-Pesa requires integers for Sandbox usually

        // 3. Create Order
        $order = Order::create([
            'order_number'     => 'ORB-' . strtoupper(Str::random(8)),
            'user_id'          => auth()->id(),
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'shipping_address' => $request->address,
            'shipping_cost'    => $shippingCost,
            'grand_total'      => $grandTotal,
            'phone'            => $phone,
        ]);

        // 4. Record Items
        foreach (Cart::getContent() as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item->id,
                'quantity'    => $item->quantity,
                'unit_price'  => $item->price, 
                'total_price' => $item->getPriceSum(),
            ]);
        }

        // 5. Trigger M-Pesa STK Push
        try {
            $mpesa = new \App\Services\MpesaService(); // Your M-Pesa logic class
            $response = $mpesa->stkPush($phone, $grandTotal, $order->order_number);

            if ($response['ResponseCode'] == "0") {
                Cart::clear();
                // Redirect to a "Processing" page where we wait for the callback
                return redirect()->route('payment.processing', $order->id)
                                 ->with('success', 'STK Push sent to your phone. Enter PIN to complete.');
            } else {
                return back()->with('error', 'M-Pesa could not be initiated. Try again.');
            }

        } catch (\Exception $e) {
            Log::error("M-Pesa Error: " . $e->getMessage());
            return back()->with('error', 'Something went wrong with the M-Pesa service.');
        }
    }
}