<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    /**
     * Handles the user redirecting back from the payment gateway.
     */
    public function handleReturn(Request $request)
    {
        $state = $request->get('state'); // 'COMPLETE', 'FAILED', or 'PENDING'
        $orderNumber = $request->get('api_ref');
        $trackingId = $request->get('tracking_id');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($state === 'COMPLETE') {
            return view('checkout.success', [
                'order' => $order,
                'tracking_id' => $trackingId
            ]);
        }

        if ($state === 'FAILED') {
            return view('checkout.failed', ['order' => $order]);
        }

        // If still pending (common with some card auths)
        return view('checkout.pending', ['order' => $order]);
    }
}