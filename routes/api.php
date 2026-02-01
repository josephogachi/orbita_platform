<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentStatusController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/**
 * 1. User Authentication
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * 2. IntaSend Payment Webhook (THE ONE TRUE ROUTE)
 * We use match(['get', 'post']) to handle:
 * - GET: IntaSend "Challenge" verification (when you save settings)
 * - POST: The actual "Payment Complete" signal
 */
Route::match(['get', 'post'], '/intasend/webhook', [PaymentStatusController::class, 'handleWebhook'])
    ->name('api.intasend.webhook');

/**
 * 3. Public Product API
 */
Route::get('/products', function() {
    return Product::all();
});