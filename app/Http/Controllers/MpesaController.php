<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Log the response so we can debug (check storage/logs/laravel.log)
        Log::info('M-Pesa Callback:', $request->all());

        $body = $request->all();

        // Check if Safaricom sent a valid response
        if (!isset($body['Body']['stkCallback'])) {
            return response()->json(['result' => 'Invalid Data'], 400);
        }

        $stkCallback = $body['Body']['stkCallback'];
        $resultCode = $stkCallback['ResultCode']; // 0 means Success

        if ($resultCode == 0) {
            // Payment Successful! 
            // In a real app, match the 'CheckoutRequestID' or Reference.
            // For now, let's mark the last Pending order as Paid.
            $order = Order::where('status', 'new')
                          ->orderBy('created_at', 'desc')
                          ->first();

            if ($order) {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'payment_method' => 'M-Pesa'
                ]);
            }
        } else {
            // Payment Failed (User cancelled, wrong PIN, etc.)
            Log::error("M-Pesa Failed: " . ($stkCallback['ResultDesc'] ?? 'Unknown Error'));
        }

        return response()->json(['result' => 'ok']);
    }
}