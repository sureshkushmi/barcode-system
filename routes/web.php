<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\ScanController;
//use App\Http\Controllers\Admin\ShippingSettingController;
use App\Http\Controllers\ShippingController;

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (shared)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes (common)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barcode Scanning Flow
    Route::get('/scan', [ScanController::class, 'scanLabelForm'])->name('scan.label.form');
    Route::post('/scan', [ScanController::class, 'handleLabel'])->name('scan.label');
    Route::get('/scan/items/{shipmentId}', [ScanController::class, 'scanItems'])->name('scan.items');
    Route::post('/scan/item/{itemId}', [ScanController::class, 'updateItem'])->name('scan.item.update');
    Route::get('/scan/next', [ScanController::class, 'nextLabel'])->name('scan.next');
    Route::get('/reports', [UserController::class, 'userReports'])->name('user.reports');
});

// User Role Routes
Route::middleware(['auth', 'role:users'])->group(function () {
    Route::get('/users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    //Route::get('/scanner', [UserController::class, 'scanner'])->name('scanner.start');
   // Route::get('/reports', [UserController::class, 'reports'])->name('user.reports');
});

// Admin Role Routes
Route::middleware(['auth', 'role:admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // User management
    Route::get('/users', [SuperadminController::class, 'index'])->name('users');
    Route::get('/users/create', [SuperadminController::class, 'create'])->name('users.create');
    Route::post('/users', [SuperadminController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [SuperadminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [SuperadminController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [SuperadminController::class, 'destroy'])->name('users.destroy');

    // Shipping Settings
    Route::get('/shipping/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
    Route::post('/shipping/save', [ShippingController::class, 'save'])->name('shipping.save');

    // Optional alternative admin shipping settings controller
    Route::get('/shipping-settings', [ShippingController::class, 'edit'])->name('settings.edit');
    Route::post('/shipping-settings', [ShippingController::class, 'update'])->name('settings.update');
    Route::get('/shipments', [ShippingController::class, 'indexShipments'])->name('shipments');
    Route::get('/shipments', [ShippingController::class, 'shipping'])->name('shipments');
 Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
