<?php

use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

// Language switch (English / Arabic)
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, config('app.locales'), true), 404);
    session(['locale' => $locale]);

    $previous = url()->previous();
    $safe = str_starts_with($previous, url('/')) ? $previous : route('home');

    return redirect($safe);
})->name('lang');

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop-count', [ShopController::class, 'count'])->middleware('throttle:60,1')->name('shop.count');
Route::get('/shop/{car}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('cars', AdminCarController::class)->except(['show']);
    Route::patch('/cars/{car}/status', [AdminCarController::class, 'status'])->name('cars.status');
    Route::delete('/cars/{car}/images/{image}', [AdminCarController::class, 'destroyImage'])->name('cars.images.destroy');

    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::delete('/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
});
