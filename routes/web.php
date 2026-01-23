<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Order;

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

Route::middleware(['auth', 'verified'])->group(function () {
    
    // User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Checkout & Payment Processing
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Payment Callback/Status (Return from IntaSend)
    Route::get('/checkout/status', [PaymentStatusController::class, 'handleReturn'])->name('payment.status');

    // E-commerce Profile & Order History
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/invoice/{order}', [ProfileController::class, 'downloadInvoice'])->name('profile.invoice');

    // Account Settings (Breeze Defaults)
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS / Internal Tools
    Route::get('/print/receipt/{order}', function (Order $order) {
        return view('filament.pages.pos-receipt', ['order' => $order]);
    })->name('print.receipt');
});

/*
|--------------------------------------------------------------------------
| Authentication System (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';