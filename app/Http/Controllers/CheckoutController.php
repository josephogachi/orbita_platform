<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;

// IMPORT BOTH CLASSES FROM INTASEND
use IntaSend\IntaSendPHP\Checkout;
use IntaSend\IntaSendPHP\Customer;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('products.index');
        }
        $cartItems = Cart::getContent();
        $total = Cart::getTotal();
        return view('checkout.index', compact('cartItems', 'total'));
    }

   public function store(Request $request)
    {
        try {
            $request->validate([
                'phone'   => 'required',
                'address' => 'required',
            ]);

            $phone = preg_replace('/[^0-9]/', '', $request->phone);
            if (str_starts_with($phone, '0')) {
                $phone = '254' . substr($phone, 1);
            }

            // Create Order
            $order = Order::create([
                'order_number'     => 'ORB-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'user_id'          => auth()->id(),
                'status'           => 'new',
                'payment_status'   => 'unpaid',
                'shipping_address' => $request->address,
                'grand_total'      => \Darryldecode\Cart\Facades\CartFacade::getTotal(),
                'phone'            => $phone,
            ]);

            foreach (\Darryldecode\Cart\Facades\CartFacade::getContent() as $item) {
                \App\Models\OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item->id,
                    'quantity'    => $item->quantity,
                    'unit_price'  => $item->price, 
                    'total_price' => $item->getPriceSum(),
                ]);
            }

            // 1. Setup Customer with simplified data
            $customer = new \IntaSend\IntaSendPHP\Customer();
            $customer->first_name = auth()->user()->name;
            $customer->last_name = "User";
            $customer->email = auth()->user()->email;
            $customer->phone_number = $phone;

            // 2. Setup Checkout with TRIMMED keys
            $checkout = new \IntaSend\IntaSendPHP\Checkout();
            $checkout->init([
                'publishable_key' => trim(env('INTASEND_PUBLISHABLE_KEY')),
                'token'           => trim(env('INTASEND_SECRET_KEY')),
                'test'            => true, 
            ]);

            // 3. THE "STRICT" CREATE CALL
            // Sometimes 500 errors are caused by the 'host' being an Ngrok URL. 
            // We will let IntaSend handle the host automatically by passing null.
            $payment = $checkout->create(
                amount: (float) \Darryldecode\Cart\Facades\CartFacade::getTotal(),
                currency: "KES",
                customer: $customer,
                host: null, // Let the SDK decide the host
                redirect_url: route('payment.status'),
                api_ref: $order->order_number,
                comment: "Order " . $order->order_number,
                method: null
            );

            \Darryldecode\Cart\Facades\CartFacade::clear();
            return redirect($payment->url);

        } catch (\Exception $e) {
             $fullError = $e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse() 
                         ? $e->getResponse()->getBody()->getContents() 
                         : $e->getMessage();
            
            // We strip the JSON to see the raw text detail
            return dd("INTASEND REJECTED: " . $fullError);
        }
    }
}