<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use Illuminate\Http\Request;
use App\Models\Scan;
use Carbon\Carbon;
// API For superadmin Dashboard ================================
// Quantity Scanned by User ====================================
Route::get('/user-scan-stats', [ScanController::class, 'getUserScanStats']);
// Scans by Status  ============================================
Route::get('/scan-status-summary', [ScanController::class, 'getScanStatus']);
// Scans Over Time   ============================================
Route::get('/scan-over-time', [ScanController::class, 'scanOverTime']);

// =========================== For Users Dashboard ==========================
//=============== My Daily Scans ==================


//Route::get('/dashboard-stats-users', [ScanController::class, 'getDashboardStatsUsers']);



    
