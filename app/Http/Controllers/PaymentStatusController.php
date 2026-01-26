<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentStatusController extends Controller
{
    /**
     * 1. User returns to site after payment
     */
    public function handleReturn(Request $request)
    {
        $orderNumber = $request->get('api_ref');
        $state = $request->get('state');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($state === 'COMPLETE' || $order->payment_status === 'paid') {
            return view('checkout.success', compact('order'));
        }

        return view('checkout.failed', compact('order'));
    }

    /**
     * 2. Silent background update (The Webhook)
     */
    public function handleWebhook(Request $request)
    {
        // STEP A: Handle IntaSend Challenge Verification
        // If IntaSend sends a challenge, we simply return it back to them
        if ($request->has('challenge')) {
            return response()->json([
                'challenge' => $request->input('challenge')
            ]);
        }

        // STEP B: Log the payment data so we can see it in storage/logs/laravel.log
        Log::info('IntaSend Webhook Data:', $request->all());

        $state = $request->input('state');
        $orderNumber = $request->input('api_ref');
        $trackingId = $request->input('tracking_id');

        // STEP C: Find the order and mark as PAID
        $order = Order::where('order_number', $orderNumber)->first();

        if ($order && $state === 'COMPLETE') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'transaction_id' => $trackingId
            ]);
            
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}