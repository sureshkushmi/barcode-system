<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\ScanController;
//use App\Http\Controllers\Admin\ShippingSettingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ReportController;

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
    Route::get('/scanitems', [ScanController::class, 'scanItemForm'])->name('scan.item.form');
    Route::get('/scan/items/{shipmentId}', [ScanController::class, 'scanItems'])->name('scan.items');



    //Route::get('/scanitems', [ScanController::class, 'scanItemForm'])->name('scan.item.form');
    //Route::post('/scan/items/{shipmentId}', [ScanController::class, 'scanItems'])->name('scan.items');
    Route::POST('/scan/item/{itemId}', [ScanController::class, 'updateItem'])->name('update.item');
    Route::get('/scan/next', [ScanController::class, 'nextShipment'])->name('next.label');
    Route::get('/reports', [UserController::class, 'userReports'])->name('user.reports');
    Route::get('/reports/user-scanning', [ReportController::class, 'userScanning'])->name('reports.user-scanning');

});

// User Role Routes
Route::middleware(['auth', 'role:users'])->group(function () {
    Route::get('/users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('/user-scanning/export', [ReportController::class, 'exportUserScanning'])->name('reports.user-scanning.export');

   // Route::get('/reports', [UserController::class, 'reports'])->name('user.reports');
   // For Users Dashboard 
   //Route::get('/weekly-scans', [ScanController::class, 'totalScansThisWeekView'])->name('weekly.scans');
   Route::middleware(['auth'])->get('/dashboard-stats-users', [ScanController::class, 'getDashboardStatsUsers']);


});

// Admin Role Routes
Route::middleware(['auth', 'role:admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // User management
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
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
    //Route::get('/reports', [UserController::class, 'userReports'])->name('reports');
    Route::get('/reports/allUser-scanning', [ReportController::class, 'allUsersScanning'])->name('reports.alluser-scanning');
    Route::get('/export-users', [ReportController::class, 'exportUsers'])->name('users.export');
    Route::get('/reports/user-scanning/export', [ReportController::class, 'exportUserScanning'])->name('reports.user-scanning.export');
    Route::get('/export-user-scanning', [ReportController::class, 'exportSuperadminUserScanning'])
    ->name('reports.user-scanning.export');
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
