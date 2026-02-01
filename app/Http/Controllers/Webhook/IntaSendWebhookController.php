<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderPaidMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IntaSendWebhookController extends Controller
{
    /**
     * Handle the IntaSend Webhook for payment confirmation.
     */
    public function handle(Request $request)
    {
        // 1. Log the incoming data for debugging purposes
        Log::info('IntaSend Webhook Received:', $request->all());

        // Extract key data from the IntaSend payload
        $state = $request->input('state');       // Expected: 'COMPLETE'
        $tracking_id = $request->input('tracking_id');
        $api_ref = $request->input('api_ref');   // Your unique 'ORB-XXXX' number

        // 2. Process only if the payment is successfully completed
        if ($state === 'COMPLETE') {
            
            // Find the order using the api_ref (order_number)
            // We eager load 'user' to ensure we have the email address for notifications
            $order = Order::with('user')->where('order_number', $api_ref)->first();

            if ($order) {
                // Check if already paid to prevent double-sending emails if webhook retries
                if ($order->payment_status === 'paid') {
                    return response()->json(['status' => 'already_processed'], 200);
                }

                // 3. Update Order record in Database
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'processing',
                    'transaction_id' => $tracking_id
                ]);

                // 4. Send Confirmation Emails
                try {
                    // Send receipt to the customer
                    Mail::to($order->user->email)->send(new OrderPaidMailable($order));

                    // Send alert to admin (Uses the mail address in your .env or a fallback)
                    $adminEmail = env('MAIL_FROM_ADDRESS', 'admin@orbita.co.ke');
                    Mail::to($adminEmail)->send(new OrderPaidMailable($order));

                    Log::info("Order {$api_ref}: Payment marked as PAID and emails dispatched.");
                } catch (\Exception $e) {
                    Log::error("Order {$api_ref}: Database updated, but emails failed. Error: " . $e->getMessage());
                }

                return response()->json(['status' => 'success'], 200);
            } else {
                Log::warning("IntaSend Webhook: Order {$api_ref} not found in database.");
            }
        }

        // Return a 200 to IntaSend even for ignored states to stop unnecessary retries
        return response()->json(['status' => 'ignored'], 200);
    }
}