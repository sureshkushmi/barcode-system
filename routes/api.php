<?php
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;
use App\Models\Scan;
use App\Models\Order;
use Carbon\Carbon;
// API For superadmin Dashboard ================================
// Quantity Scanned by User ====================================
Route::get('/user-scan-stats', [ScanController::class, 'getUserScanStats']);
Route::get('/top-customers', [OrderController::class, 'topCustomerOrders']);
// Scans by Status  ============================================
Route::get('/scan-status-summary', [ScanController::class, 'getScanStatus']);
// Scans Over Time   ============================================
Route::get('/scan-over-time', [ScanController::class, 'scanOverTime']);

// =========================== For Users Dashboard ==========================
//=============== My Daily Scans ==================


//Route::get('/dashboard-stats-users', [ScanController::class, 'getDashboardStatsUsers']);



    
