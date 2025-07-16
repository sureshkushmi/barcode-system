<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\ScanController;
//use App\Http\Controllers\Admin\ShippingSettingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController as UserMessageController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;


// Public route
Route::get('/', function () {
    return view('welcome');
});
Route::post('/logoutusers', function () {
    Auth::logout();
    return redirect('https://scan.merodomain.com/public/login');
})->name('logoutusers');
// Dashboard (shared)

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//Route::get('/scanapistore', [UserMessageController::class, 'syncOrdersFromShippingEasyTest']);

// For testing approach
Route::get('/syncOrders', [OrderController::class, 'syncOrdersFromShippingEasyTest']);
Route::get('/check-tracking/{tracking_number}', [OrderController::class, 'checkTrackingNumber']);
Route::get('/scanapi', [ScanController::class, 'test1']);

// Authenticated routes (common)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barcode Scanning Flow
    Route::get('/scan', [ScanController::class, 'scanLabelForm'])->name('scan.label.form');
    //Route::post('/scan', [ScanController::class, 'handleLabel'])->name('scan.label');
    Route::get('/scan-label', function () {
        return view('scan.label'); // Replace with actual view path
    })->name('scan.label.form');
   

    Route::post('/scan-label', [ScanController::class, 'handleLabel'])->name('scan.label');

    Route::get('/scanitems', [ScanController::class, 'scanItemForm'])->name('scan.item.form');

    Route::get('/scan-options/{shipment}', [ScanController::class, 'showScanOptions'])->name('scan.options');

    Route::get('/scan-items/{shipment}', [ScanController::class, 'scanItems'])->name('scan.items');

    Route::get('/scan-kit/{kit}', [ScanController::class, 'scanKit'])->name('scan.kit');

    Route::get('/shipment/{shipment}/packing-list', [ScanController::class, 'showPackingList'])->name('packing.list');


        // Handle submissions
    Route::post('/submit-kit-scan/{kit}', [ScanController::class, 'submitKitScan'])->name('submit.kit.scan');
    Route::post('/submit-item-scan/{shipment}', [ScanController::class, 'submitItemScan'])->name('submit.item.scan');
    Route::POST('/scan/item/{itemId}', [ScanController::class, 'updateItem'])->name('update.item');
    Route::get('/scan/next', [ScanController::class, 'nextShipment'])->name('next.label');
    Route::get('/reports', [UserController::class, 'userReports'])->name('user.reports');
    Route::get('/reports/user-scanning', [ReportController::class, 'userScanning'])->name('reports.user-scanning');
        // for message ========================================================================
     
    Route::get('/messages', [UserMessageController::class, 'userInbox'])->name('users.messages.index');
    //Route::get('/messages/conversation/{sender_id}', [UserMessageController::class, 'viewConversation']);
    //Route::post('/messages/mark-read', [MessageController::class, 'markAsRead']);
    Route::get('messages/conversation/{sender_id}', [UserMessageController::class, 'viewConversation'])->name('users.messages.conversation');
    Route::get('/messages/unread', [UserMessageController::class, 'unreadMessages']);
    Route::post('/messages/mark-all-read', [UserMessageController::class, 'markAllAsRead']);
});

// User Role Routes
Route::middleware(['auth', 'role:users'])->group(function () {
    Route::get('/users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('/user-scanning/export', [ReportController::class, 'exportUserScanning'])->name('reports.user-scanning.export');
    Route::get('/my-scan-orders', [UserController::class, 'userScannedOrders'])->name('user.scan.orders');
    Route::get('/user/shipment-details/{shipmentId}', [UserController::class, 'getShipmentDetails'])->name('user.shipment.details');

    Route::get('/relatedUser/export', [ReportController::class, 'exportUserScanning'])->name('reports.relatedUser-scanning.export');
    Route::get('/users/scanning', [ReportController::class, 'usersScanningReport'])->name('reports.users-scanning');

   //==================== For orders ================================================
   Route::get('/users.pending-orders', [UserController::class, 'userPendingOrders'])->name('user.scan.pending-orders');
   Route::get('/users.orders', [OrderController::class, 'index'])->name('users.orders');
    Route::get('users/syncOrders', [OrderController::class, 'syncOrdersFromShippingEasy'])->name('users.syncOrdersFromShippingEasy');
   

      //==================== For orders ================================================
   Route::get('/scaning', [ScanController::class, 'test']);

   Route::middleware(['auth'])->get('/dashboard-stats-users', [ScanController::class, 'getDashboardStatsUsers']);
//=========================== For messages=====================
    Route::post('/messages/reply', [App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');


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
    Route::get('/reports/{user}/relatedUser-scanning', [ReportController::class, 'relatedUsersScanning'])->name('reports.relateduser-scanning');

     //==================== For orders ================================================
      Route::get('/syncOrders', [OrderController::class, 'syncOrdersFromShippingEasy'])->name('syncOrdersFromShippingEasy');
     Route::get('/orders', [OrderController::class, 'index'])->name('orders');
     Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
     Route::get('/user-scan-report/{id}', [UserController::class, 'getScanReport'])->name('user.scanReport');



         //============================== For Message only ==========================================
    Route::get('/messages/inbox', [AdminMessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/sent', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create', [AdminMessageController::class, 'create'])->name('messages.create');
    Route::post('messages/store', [AdminMessageController::class, 'store'])->name('messages.store');
    Route::get('messages/{id}', [AdminMessageController::class, 'show'])->name('messages.show');

    Route::post('/messages/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');
    Route::get('/messages/{id}/replies', [AdminMessageController::class, 'fetchReplies']);

    //======================================End Messages ===========================================
    
    Route::get('/export-users', [ReportController::class, 'exportUsers'])->name('users.export');
    Route::get('/reports/user-scanning/export', [ReportController::class, 'exportUserScanning'])->name('reports.user-scanning.export');
    Route::get('/export-user-scanning', [ReportController::class, 'exportSuperadminUserScanning'])
    ->name('reports.user-scanning.export');
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
