<?php

use App\Http\Controllers\PaymentCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * 1. User Authentication (Default)
 * Allows authenticated mobile apps or JS frontend to get user data.
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * 2. Payment Gateway Webhook (Production Ready)
 * This is the endpoint the payment gateway (Intasend/Pesapal) calls 
 * to confirm payment success.
 */
Route::post('/payment/webhook', [PaymentCallbackController::class, 'handleWebhook'])
    ->name('api.payment.webhook');

/**
 * 3. Public Product API (Optional)
 * Useful if you ever build a mobile app or need to fetch products via JS.
 */
Route::get('/products', function() {
    return \App\Models\Product::where('is_active', true)->get();
});