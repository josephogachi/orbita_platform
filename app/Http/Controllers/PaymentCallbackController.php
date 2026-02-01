<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentCallbackController extends Controller
{
    /**
     * Secret Webhook triggered by the Payment Gateway (Intasend/Pesapal).
     */
    public function handleWebhook(Request $request)
    {
        // 1. Log the payload for debugging
        Log::info('Payment Webhook Received:', $request->all());

        $orderNumber = $request->input('api_ref');
        $state = $request->input('state');
        $amountReceived = $request->input('value');
        $trackingId = $request->input('tracking_id');

        // 2. Find the order with items and products loaded
        $order = Order::with(['items.product'])->where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::error("Webhook Error: Order {$orderNumber} not found.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Process Success
        if ($state === 'COMPLETE') {
            // Security check: Ensure the amount paid matches the grand_total
            if ((float)$amountReceived >= (float)$order->grand_total) {
                
                // Update Order Status
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_method' => $request->input('provider'), // mpesa or card
                ]);

                try {
                    // GENERATE PDF INVOICE
                    // The view 'pdf.invoice' uses the BBS Mall and Decale Palace addresses
                    $pdf = Pdf::loadView('pdf.invoice', [
                        'order' => $order,
                        'tracking_id' => $trackingId
                    ]);

                    // SEND EMAIL TO CUSTOMER
                    Mail::send([], [], function ($message) use ($order, $pdf) {
                        $message->to($order->customer_email ?? $order->user->email)
                            ->subject("Your Orbita Kenya Ltd Invoice - {$order->order_number}")
                            ->attachData($pdf->output(), "Invoice_{$order->order_number}.pdf", [
                                'mime' => 'application/pdf',
                            ])
                            ->html("<p>Dear Customer,</p><p>Please find attached the invoice for your recent purchase at Orbita Kenya Ltd.</p>");
                    });

                    Log::info("Order {$orderNumber}: Payment recorded and Invoice emailed.");
                } catch (\Exception $e) {
                    Log::error("Order {$orderNumber}: Payment succeeded but email/PDF failed: " . $e->getMessage());
                }

                return response()->json(['message' => 'Payment recorded and Invoice sent'], 200);
            } else {
                Log::warning("Order {$orderNumber} underpaid. Received: {$amountReceived}");
            }
        }

        // 4. Process Failure
        if ($state === 'FAILED') {
            $order->update(['status' => 'cancelled']);
            return response()->json(['message' => 'Order cancelled'], 200);
        }

        return response()->json(['message' => 'Callback processed'], 200);
    }
}