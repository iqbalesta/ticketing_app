<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\HistoriesController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Events (User)
Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

// Orders (User) - requires auth
Route::middleware('auth')->group(function () {
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes - dilindungi middleware admin
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Event Management
    Route::resource('events', AdminEventController::class);
    
    // Tiket Management
    Route::resource('tickets', TiketController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    // Give 'tiket' an alias to 'tickets' for consistency
    Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
    
    // Lokasi Management - moved to admin group
    Route::resource('lokasi', \App\Http\Controllers\LokasiController::class)->names([
        'index' => 'lokasi.index',
        'create' => 'lokasi.create',
        'store' => 'lokasi.store',
        'show' => 'lokasi.show',
        'edit' => 'lokasi.edit',
        'update' => 'lokasi.update',
        'destroy' => 'lokasi.destroy',
    ]);
    
    // Histories
    Route::get('/histories', [HistoriesController::class, 'index'])->name('histories.index');
    Route::get('/histories/{id}', [HistoriesController::class, 'show'])->name('histories.show');
    
    // Resource routes untuk admin (legacy)
    Route::resource('kategori', KategoriController::class);
    Route::resource('order', OrderController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
