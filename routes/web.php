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
use App\Http\Controllers\SitemapController;
use App\Models\Campaign;
use Illuminate\Support\Facades\Artisan;
use App\Models\PartnershipApplication;
use App\Http\Controllers\JobController;
use App\Models\Faq;

// The Sitemap Route
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

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

// Careers / Job Portal
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{slug}/apply', [JobController::class, 'apply'])->name('jobs.apply');

// Products & Cart
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');

// FAQ Page
Route::get('/faq', function () {
    $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();

    return view('faq', [
        'faqs' => $faqs,
        'seo_title' => 'Frequently Asked Questions | Orbita Kenya',
        'seo_description' => 'Answers to common questions about hotel smart locks, RFID systems, minibars, and installation services in Kenya.'
    ]);
})->name('faq');

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

// Secure Catalog Download Route
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

// Email tracking
Route::get('/m/t/{campaign_id}/{email}', function ($campaignId, $email) {
    $campaign = Campaign::find($campaignId);

    if ($campaign) {
        $log = $campaign->status_log ?? [];
        $log[$email] = now()->toDateTimeString();
        $campaign->update(['status_log' => $log]);
    }

    return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'), 200)
        ->header('Content-Type', 'image/gif')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
})->name('campaign.track');

// WhatsApp PDF Download Link
Route::get('/quotations/{quotation}/download', function (\App\Models\Quotation $quotation) {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation', ['quotation' => $quotation])
                ->setPaper('a4', 'portrait');
                
    return $pdf->download("QT-{$quotation->quotation_number}.pdf");
})->name('quotation.download');

// Partnership Application Routes
Route::get('/partnership', function () {
    return view('partnership');
})->name('partnership');

Route::post('/partnership', function (Request $request) {
    $validated = $request->validate([
        'company_name' => 'required|string|max:255',
        'kra_pin' => 'required|string|max:255',
        'business_type' => 'required|string|max:255',
        'years_active' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:255',
        'physical_address' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'team_size' => 'required|string|max:255',
        'proposal' => 'required|string',
    ]);

    PartnershipApplication::create($validated);

    return back()->with('success', 'Application received successfully!');
})->name('partnership.store');

// Logistics PDF Export
Route::get('/logistics/export-pdf', function () {
    $ids = session('logistics_print_ids', []);
    
    if (empty($ids)) {
        return "No products selected for export. Please go back and select products.";
    }

    $products = \App\Models\LogisticsProduct::whereIn('id', $ids)->get();

    return view('exports.logistics_pdf', compact('products'));
});

/*
|--------------------------------------------------------------------------
| UTILITY / UPGRADE ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/scrub-blades', function () {
    $directory = resource_path('views');
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    $scrubbedCount = 0;
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $clean_content = str_replace("\xC2\xA0", ' ', $content);
            if ($content !== $clean_content) {
                file_put_contents($file->getPathname(), $clean_content);
                $scrubbedCount++;
            }
        }
    }
    
    $path = storage_path('framework/views');
    $files = glob($path . '/*');
    $deletedCache = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deletedCache++;
        }
    }
    
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    
    return "SUCCESS! Scrubbed {$scrubbedCount} Blade files of invisible cPanel characters and destroyed {$deletedCache} ghost cache files. Go refresh your page!";
});

Route::get('/upgrade-campaigns', function () {
    $updates = [];

    // 1. Check and add scheduled_at independently
    if (!\Illuminate\Support\Facades\Schema::hasColumn('campaigns', 'scheduled_at')) {
        \Illuminate\Support\Facades\Schema::table('campaigns', function ($table) {
            $table->dateTime('scheduled_at')->nullable();
        });
        $updates[] = "Added 'scheduled_at' column.";
    }

    // 2. Check and add status independently
    if (!\Illuminate\Support\Facades\Schema::hasColumn('campaigns', 'status')) {
        \Illuminate\Support\Facades\Schema::table('campaigns', function ($table) {
            $table->string('status')->default('draft');
        });
        $updates[] = "Added 'status' column.";
    }

    if (empty($updates)) {
        return 'The columns already exist! You are good to go.';
    }

    return 'SUCCESS! Updates applied: ' . implode(' ', $updates);
});

Route::get('/run-scheduler', function () {
    $now = now();
    $campaigns = \App\Models\Campaign::where('status', 'scheduled')->get();
    
    $output = "<div style='font-family: sans-serif; padding: 20px;'>";
    $output .= "<h2 style='color: #d97706;'>🕰️ Server Clock X-Ray</h2>";
    $output .= "<strong>Laravel App Time:</strong> " . $now->toDateTimeString() . " (" . $now->getTimezone()->getName() . ")<br><br>";
    
    $output .= "<h2 style='color: #2563eb;'>📧 Scheduled Campaigns Found: " . $campaigns->count() . "</h2>";
    
    foreach ($campaigns as $c) {
        $isDue = $c->scheduled_at <= $now;
        $statusColor = $isDue ? 'green' : 'red';
        $statusText = $isDue ? 'YES (Should Send Now)' : 'NO (Still Waiting)';
        
        $output .= "Campaign ID: <strong>{$c->id}</strong><br>";
        $output .= "Scheduled For: <strong>{$c->scheduled_at}</strong><br>";
        $output .= "Is it time yet? <strong style='color: {$statusColor};'>{$statusText}</strong><hr>";
    }
    
    // Clear config cache to force the new timezone
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    
    // Run the scheduler
    \Illuminate\Support\Facades\Artisan::call('schedule:run');
    
    $output .= "<br><h2 style='color: #059669;'>⚙️ Scheduler Output:</h2>";
    $output .= "<pre style='background: #1e293b; color: #10b981; padding: 15px; border-radius: 8px;'>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    $output .= "</div>";
    
    return $output;
});

Route::get('/install-job-portal', function () {
    $updates = [];

    // 1. Create Job Postings Table
    if (!\Illuminate\Support\Facades\Schema::hasTable('job_postings')) {
        \Illuminate\Support\Facades\Schema::create('job_postings', function ($table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department')->nullable();
            $table->string('location')->default('Nairobi, Kenya');
            $table->string('employment_type')->default('Full-time');
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->boolean('is_published')->default(true);
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });
        $updates[] = "Created 'job_postings' table.";
    }

    // 2. Create Job Applications Table
    if (!\Illuminate\Support\Facades\Schema::hasTable('job_applications')) {
        \Illuminate\Support\Facades\Schema::create('job_applications', function ($table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('resume_path'); // Where the PDF/Word doc is saved
            $table->string('portfolio_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, shortlisted, rejected
            $table->timestamps();
        });
        $updates[] = "Created 'job_applications' table.";
    }

    if (empty($updates)) {
        return 'The Job Portal tables already exist! You are good to go.';
    }

    return 'SUCCESS! The Job Portal database has been installed: ' . implode(' ', $updates);
});

Route::get('/generate-hr-pages', function () {
    \Illuminate\Support\Facades\Artisan::call('make:filament-page', ['name' => 'ListJobPostings', '--resource' => 'JobPostingResource', '--type' => 'ListRecords']);
    \Illuminate\Support\Facades\Artisan::call('make:filament-page', ['name' => 'CreateJobPosting', '--resource' => 'JobPostingResource', '--type' => 'CreateRecord']);
    \Illuminate\Support\Facades\Artisan::call('make:filament-page', ['name' => 'EditJobPosting', '--resource' => 'JobPostingResource', '--type' => 'EditRecord']);
    
    \Illuminate\Support\Facades\Artisan::call('make:filament-page', ['name' => 'ListJobApplications', '--resource' => 'JobApplicationResource', '--type' => 'ListRecords']);
    \Illuminate\Support\Facades\Artisan::call('make:filament-page', ['name' => 'ViewJobApplication', '--resource' => 'JobApplicationResource', '--type' => 'ViewRecord']);
    
    return "SUCCESS! HR Pages generated. Check your admin panel!";
});

Route::get('/add-invoice-features', function () {
    \Illuminate\Support\Facades\Schema::table('orders', function ($table) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'installation_fee')) {
            $table->decimal('installation_fee', 10, 2)->default(0)->after('shipping_cost');
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'exchange_rate')) {
            $table->decimal('exchange_rate', 10, 2)->default(130)->after('currency');
        }
    });
    return 'SUCCESS! Database updated.';
});

/*
|--------------------------------------------------------------------------
| 404 CATCHER & REDIRECTOR (Must remain at the very bottom)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    $requestedUrl = request()->path();
    
    $redirect = \App\Models\Redirect::where('old_url', $requestedUrl)
        ->orWhere('old_url', '/' . $requestedUrl)
        ->first();
        
    if ($redirect) {
        return redirect($redirect->new_url, $redirect->status_code);
    }
    
    abort(404);
});

require __DIR__.'/auth.php';