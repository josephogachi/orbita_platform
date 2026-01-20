<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // <--- THIS LINE IS CRITICAL

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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

require __DIR__.'/auth.php';
