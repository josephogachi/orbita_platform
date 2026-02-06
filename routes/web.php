<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\MpesaController;
use App\Livewire\CheckoutPage;
use App\Livewire\ContactPage;
use App\Livewire\ProjectQuoteForm;
use App\Models\Order;
use App\Models\ShopSetting;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscriber;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about', [
        'settings' => ShopSetting::first() 
    ]);
})->name('about');

Route::get('/contact', ContactPage::class)->name('contact');

// Products & Cart
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Client Area)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Checkout Flow
    Route::get('/checkout', CheckoutPage::class)->name('checkout');

    // M-Pesa Processing Page
    Route::get('/payment/processing/{order}', function (Order $order) {
        return view('payment-processing', ['order' => $order]);
    })->name('payment.processing');

    // AJAX Status Check (for Polling)
    Route::get('/api/check-order-status/{order}', function (Order $order) {
        return response()->json(['status' => $order->payment_status]);
    });

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

    // Project Quotes
    Route::get('/request-quote/{product_id?}', ProjectQuoteForm::class)->name('quotes.create');
});

/*
|--------------------------------------------------------------------------
| Work / Portfolio Routes
|--------------------------------------------------------------------------
*/

Route::get('/work', function () {
    $projects = Project::where('is_active', true)
        ->orderBy('completion_date', 'desc')
        ->get();
    return view('work', ['projects' => $projects]);
})->name('work');

Route::get('/work/{slug}', function ($slug) {
    $project = Project::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();
    return view('project-show', ['project' => $project]);
})->name('work.show');

/*
|--------------------------------------------------------------------------
| Payment Callbacks & Webhooks
|--------------------------------------------------------------------------
*/

Route::get('/checkout/status', [PaymentStatusController::class, 'handleReturn'])->name('payment.status');
Route::post('/mpesa/callback', [MpesaController::class, 'handleCallback'])->name('mpesa.callback');
Route::post('/intasend/webhook', [PaymentStatusController::class, 'handleWebhook']);

/*
|--------------------------------------------------------------------------
| Utilities & Downloads
|--------------------------------------------------------------------------
*/

// Secure Catalog Download Route (Cleaned & Updated)
// routes/web.php

Route::get('/download-catalog', function () {
    // 1. Guest Check: Show the creative Gateway Page
    if (!auth()->check()) {
        return view('catalog-gate');
    }

    // 2. Logged in Logic
    $settings = \App\Models\ShopSetting::first();

    // Check if file exists in DB and Storage
    if (!$settings || empty($settings->catalog_path) || !\Illuminate\Support\Facades\Storage::disk('public')->exists($settings->catalog_path)) {
        return back()->with('error', 'The product catalog is currently being updated. Please try again later.');
    }

    // 3. Download the file
    return \Illuminate\Support\Facades\Storage::disk('public')->download($settings->catalog_path, 'Orbita_Kenya_Catalog.pdf');
    
})->name('catalog.download');

// Debug Route (Optional - Good to keep for checking storage links)
Route::get('/debug-config', function () {
    $settings = ShopSetting::first();
    if (!$settings) return "❌ No ShopSettings found.";
    
    return [
        'Logo Path' => $settings->logo_path,
        'Catalog Path' => $settings->catalog_path,
        'Catalog Exists on Disk?' => Storage::disk('public')->exists($settings->catalog_path ?? '') ? '✅ YES' : '❌ NO',
        'App Environment' => config('app.env'),
    ];
});

/*
|--------------------------------------------------------------------------
| Legal & Info Pages
|--------------------------------------------------------------------------
*/
Route::view('/installation-guide', 'policies.installation')->name('policy.installation');
Route::view('/warranty-policy', 'policies.warranty')->name('policy.warranty');
Route::view('/privacy-policy', 'policies.privacy')->name('policy.privacy');
Route::view('/terms-of-service', 'policies.terms')->name('policy.terms');

// Subscriber Route (for the popup)
Route::post('/subscribe', [App\Http\Controllers\SubscriberController::class, 'store'])->name('subscribe.store');

// The unsubscribe route
Route::get('/unsubscribe/{id}', function ($id) {
    $subscriber = Subscriber::findOrFail($id);
    $subscriber->update(['is_active' => false]);

    return view('policies.unsubscribed', ['email' => $subscriber->email]);
})->name('unsubscribe');

require __DIR__.'/auth.php';