<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::get('/user-scan-stats', [ScanController::class, 'getUserScanStats']);
Route::get('/scan-status-summary', [ScanController::class, 'getScanStatus']);
Route::get('/scan-trend-week', [ScanController::class, 'weeklyScans']);
Route::get('/dashboard/scan-summary', [ScanController::class, 'getScanSummary'])->name('dashboard.scanSummary');
use Illuminate\Http\Request;
use App\Models\Scan;
use Carbon\Carbon;

Route::get('/scan-trend-week', function (Request $request) {
    $user = $request->user();

    // Get last 7 days dates
    $dates = collect();
    for ($i = 6; $i >= 0; $i--) {
        $dates->push(Carbon::today()->subDays($i)->format('M d'));
    }

    // Query scans grouped by day for current user for last 7 days
    $scans = Scan::selectRaw('DATE(scanned_at) as date, COUNT(*) as count')
        ->where('user_id', $user->id)
        ->whereBetween('scanned_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
        ->groupBy('date')
        ->pluck('count', 'date');

    // Map data counts for each day (0 if no scans)
    $data = $dates->map(function($date) use ($scans) {
        $dbDate = Carbon::createFromFormat('M d', $date)->format('Y-m-d');
        return $scans->get($dbDate, 0);
    });

    return response()->json([
        'labels' => $dates,
        'data' => $data,
    ]);
});

Route::get('/scan-status-overview', function (Request $request) {
    $user = $request->user();

    // Query count by status for current user
    $statusCounts = Scan::where('user_id', $user->id)
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

    return response()->json([
        'completed' => $statusCounts->get('completed', 0),
        'pending' => $statusCounts->get('pending', 0),
        'failed' => $statusCounts->get('failed', 0),
    ]);
});

Route::get('/user-scan-trend', function (Request $request) {
    $user = $request->user();

    $today = Carbon::today();
    $startDate = $today->copy()->subDays(6); // last 7 days including today

    // Get counts grouped by date for this user
    $scans = Scan::where('user_id', $user->id)
        ->whereBetween('scanned_at', [$startDate->startOfDay(), $today->endOfDay()])
        ->select(DB::raw('DATE(scanned_at) as date'), DB::raw('COUNT(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date');

    $labels = [];
    $data = [];

    // Prepare labels & data for last 7 days, filling zeros if no data
    for ($i = 0; $i < 7; $i++) {
        $date = $startDate->copy()->addDays($i);
        $label = $date->format('D'); // e.g. 'Wed', 'Thu'
        $dateStr = $date->toDateString();

        $labels[] = $label;
        $data[] = $scans->get($dateStr, 0);
    }

    return response()->json([
        'labels' => $labels,
        'data' => $data
    ]);
});


    
