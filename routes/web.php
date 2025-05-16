<?php
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/scan', [\App\Http\Controllers\ScanController::class, 'scanLabelForm'])->name('scan.label.form');
    Route::post('/scan', [\App\Http\Controllers\ScanController::class, 'handleLabel'])->name('scan.label');
    Route::get('/scan/items/{shipmentId}', [\App\Http\Controllers\ScanController::class, 'scanItems'])->name('scan.items');
    Route::post('/scan/item/{itemId}', [\App\Http\Controllers\ScanController::class, 'updateItem'])->name('scan.item.update');
    Route::get('/scan/next', [\App\Http\Controllers\ScanController::class, 'nextLabel'])->name('scan.next');
});

Route::middleware(['auth', 'role:users'])->group(function () {
    Route::get('/users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('/scanner', [UserController::class, 'scanner'])->name('scanner.start');
    Route::get('/reports', [UserController::class, 'reports'])->name('user.reports');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/superadmin/users', [SuperadminController::class, 'index'])->name('superadmin.users');

    // ADD
    Route::get('/superadmin/users/create', [SuperadminController::class, 'create'])->name('superadmin.users.create');
    Route::post('/superadmin/users', [SuperadminController::class, 'store'])->name('superadmin.users.store');

    // EDIT
    Route::get('/superadmin/users/{id}/edit', [SuperadminController::class, 'edit'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{id}', [SuperadminController::class, 'update'])->name('superadmin.users.update');

    // DELETE
    Route::delete('/superadmin/users/{id}', [SuperadminController::class, 'destroy'])->name('superadmin.users.destroy');
    Route::get('shipping-settings', [\App\Http\Controllers\Admin\ShippingSettingController::class, 'edit'])->name('shipping.edit');
    Route::post('shipping-settings', [\App\Http\Controllers\Admin\ShippingSettingController::class, 'update'])->name('shipping.save');
});


require __DIR__.'/auth.php';
