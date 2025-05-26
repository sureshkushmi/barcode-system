<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Scans;
use Illuminate\Support\Facades\DB;
use App\Exports\UserScanningExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuperadminUserScanningExport;

class ReportController extends Controller
{
    
    public function allUsersScanning(Request $request)
{
    $query = DB::table('shipments')
        ->leftJoin('scans', 'shipments.id', '=', 'scans.shipment_id')
        ->leftJoin('items', 'scans.item_id', '=', 'items.id')
        ->select(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            DB::raw('COALESCE(items.required_quantity, 0) as total_qty'),
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at'),
            DB::raw("CASE 
                WHEN SUM(scans.quantity_scanned) >= COALESCE(items.required_quantity, 0) THEN 'Completed'
                WHEN SUM(scans.quantity_scanned) > 0 THEN 'In Progress'
                ELSE 'Pending'
            END as scan_status")
        )
        ->groupBy(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            'items.required_quantity'
        )
        ->orderByDesc('scanned_at');

    if ($request->filled('datetimes')) {
        [$start, $end] = explode(' - ', $request->input('datetimes'));
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $query->whereBetween('scans.scanned_at', [$start, $end]);
    }

    $shipments = $query->paginate(10);

    // ✅ Fixed item-wise scan detail query
    $shipmentIds = $shipments->pluck('id');

    $shipmentIds = $shipments->pluck('id');

$scannedItems = DB::table('scans')
    ->join('items', 'scans.item_id', '=', 'items.id')
    ->join('users', 'scans.user_id', '=', 'users.id') // Join users here
    ->whereIn('scans.shipment_id', $shipmentIds)
    ->select(
        'scans.shipment_id',
        'items.name as item_name',
        'items.barcode',
        'items.required_quantity as total_quantity',
        'scans.quantity_scanned',
        'users.name as scanned_by',
        'scans.scanned_at'
    )
    ->get()
    ->groupBy('shipment_id');


    return view('superadmin.reports', compact('shipments', 'scannedItems'));
}



public function userScanning(Request $request)
{
    $userId = auth()->id(); // Current logged-in user ID

    $query = DB::table('shipments')
        ->join('scans', function ($join) use ($userId) {
            $join->on('shipments.id', '=', 'scans.shipment_id')
                 ->where('scans.user_id', $userId);
        })
        ->leftJoin('items', 'scans.item_id', '=', 'items.id')
        ->leftJoin('users', 'scans.user_id', '=', 'users.id')  // Join users to get scanner name
        ->select(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            DB::raw('COALESCE(items.required_quantity, 0) as total_qty'),
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at'),
            DB::raw("CASE 
                WHEN SUM(scans.quantity_scanned) >= COALESCE(items.required_quantity, 0) THEN 'Completed'
                WHEN SUM(scans.quantity_scanned) > 0 THEN 'In Progress'
                ELSE 'Pending'
            END as scan_status")
        )
        ->groupBy(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            'items.required_quantity'
        )
        ->orderByDesc('scanned_at');

    // Filter by date range if provided
    if ($request->filled('datetimes')) {
        [$start, $end] = explode(' - ', $request->input('datetimes'));
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $query->whereBetween('scans.scanned_at', [$start, $end]);
    }

    $shipments = $query->paginate(10);

    $shipmentIds = $shipments->pluck('id');

    $scannedItems = DB::table('scans')
        ->join('items', 'scans.item_id', '=', 'items.id')
        ->join('users', 'scans.user_id', '=', 'users.id')  // Join users for scanner name
        ->where('scans.user_id', $userId)                 // Limit to current user
        ->whereIn('scans.shipment_id', $shipmentIds)
        ->select(
            'scans.shipment_id',
            'items.name as item_name',
            'items.barcode',
            'items.required_quantity as total_quantity',
            'scans.quantity_scanned',
            'users.name as scanned_by',   // user name here
            'scans.scanned_at'
        )
        ->get()
        ->groupBy('shipment_id');

    return view('users.reports', compact('shipments', 'scannedItems'));
}


//================== For export =================
public function exportUserScanning(Request $request)
{
    $userId = auth()->id();

    $query = DB::table('shipments')
        ->join('scans', function ($join) use ($userId) {
            $join->on('shipments.id', '=', 'scans.shipment_id')
                ->where('scans.user_id', $userId);
        })
        ->leftJoin('items', 'scans.item_id', '=', 'items.id')
        ->leftJoin('users', 'scans.user_id', '=', 'users.id')
        ->select(
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            DB::raw('COALESCE(items.required_quantity, 0) as total_qty'),
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at'),
            DB::raw("CASE 
                WHEN SUM(scans.quantity_scanned) >= COALESCE(items.required_quantity, 0) THEN 'Completed'
                WHEN SUM(scans.quantity_scanned) > 0 THEN 'In Progress'
                ELSE 'Pending'
            END as scan_status")
        )
        ->groupBy(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.created_at',
            'shipments.updated_at',
            'items.required_quantity'
        );

    if ($request->filled('datetimes')) {
        [$start, $end] = explode(' - ', $request->input('datetimes'));
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $query->whereBetween('scans.scanned_at', [$start, $end]);
    }

    $shipments = $query->get();

    return Excel::download(new UserScanningExport($shipments), 'user_scanning_report.xlsx');
}


public function exportUsers()
{
    $users = User::all(); // No roles relationship
    return Excel::download(new UsersExport($users), 'users.xlsx');
}

public function exportSuperadminUserScanning()
{
    // Get all shipments or filtered based on your logic
    $shipments = Shipment::all();

    // Or add filters, e.g., date range or status based on request

    return Excel::download(new SuperadminUserScanningExport($shipments), 'superadmin_user_scanning_report.xlsx');
}


}
