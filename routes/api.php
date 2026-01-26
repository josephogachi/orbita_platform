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
 * 2. IntaSend Payment Webhook
 * We add BOTH POST and GET because IntaSend uses GET for the initial 
 * "Challenge" verification and POST for the actual payment data.
 */
Route::match(['get', 'post'], '/intasend/webhook', [PaymentStatusController::class, 'handleWebhook'])
    ->name('api.intasend.webhook');

/**
 * 3. Public Product API
 */
Route::get('/products', function() {
    return Product::all();
});