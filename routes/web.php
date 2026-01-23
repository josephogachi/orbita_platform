<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // <--- THIS LINE IS CRITICAL
use App\Http\Controllers\ProductController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // E-commerce Dashboard
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/invoice/{order}', [ProfileController::class, 'downloadInvoice'])->name('profile.invoice');

    // Account Settings (Breeze Defaults)
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/print/receipt/{order}', function (Order $order) {
    // Only allow printing if the user is logged in
    if (!auth()->check()) {
        return redirect('/admin/login');
    }
    return view('filament.pages.pos-receipt', ['order' => $order]);
})->name('print.receipt');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/checkout/status', [PaymentStatusController::class, 'handleReturn'])->name('payment.status');

require __DIR__.'/auth.php';
