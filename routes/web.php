<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Http\Controllers\Auth\GoogleController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home & Marketing
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/work', function () { return view('work'); })->name('work');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// Products Gallery & Detail
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Shopping Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Requires Login)
|--------------------------------------------------------------------------
*/

// Using only 'auth' to prevent redirect loops from unverified emails
Route::middleware(['auth'])->group(function () {
    
    // User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Checkout Flow
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Payment Callback Status
    Route::get('/checkout/status', [PaymentStatusController::class, 'handleReturn'])->name('payment.status');

    // Profile & Order History
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/settings', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::get('/profile/invoice/{order}', 'downloadInvoice')->name('profile.invoice');
    });

    // POS / Internal Printing
    Route::get('/print/receipt/{order}', function (Order $order) {
        return view('filament.pages.pos-receipt', ['order' => $order]);
    })->name('print.receipt');
});
// Google Authentication Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/checkout/status', [PaymentStatusController::class, 'handleReturn'])->name('payment.status');
Route::post('/intasend/webhook', [PaymentStatusController::class, 'handleWebhook']);
require __DIR__.'/auth.php';