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
        ->select(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.completed',
            'shipments.created_at',
            'shipments.updated_at',
            DB::raw('COALESCE(shipments.completed, 0) as total_qty'), // assuming this field stores total qty
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at')
        )
        ->groupBy(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.completed',
            'shipments.created_at',
            'shipments.updated_at'
        )
        ->orderByDesc('scanned_at');

    // Optional: Date filtering
    if ($request->filled('datetimes')) {
        [$start, $end] = explode(' - ', $request->input('datetimes'));
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $query->whereBetween('scans.scanned_at', [$start, $end]);
    }

    $shipments = $query->paginate(10);

    return view('superadmin.reports', compact('shipments'));
}    

public function userScanning(Request $request)

{
    $userId = auth()->id(); // Get current logged-in user ID
    $query = DB::table('shipments')
    ->join('scans', function ($join) use ($userId) {
        $join->on('shipments.id', '=', 'scans.shipment_id')
             ->where('scans.user_id', $userId);
    })
    // rest of your query

        ->select(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.completed',
            'shipments.created_at',
            'shipments.updated_at',
            DB::raw('COALESCE(shipments.completed, 0) as total_qty'), // assuming this field stores total qty
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at')
        )
        ->groupBy(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.status',
            'shipments.completed',
            'shipments.created_at',
            'shipments.updated_at'
        )
        ->orderByDesc('scanned_at');

    // Optional: Date filtering
    if ($request->filled('datetimes')) {
        [$start, $end] = explode(' - ', $request->input('datetimes'));
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $query->whereBetween('scans.scanned_at', [$start, $end]);
    }

    $shipments = $query->paginate(10);

    return view('users.reports', compact('shipments'));
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
        ->select(
            'shipments.tracking_number',
            DB::raw('COALESCE(shipments.completed, 0) as total_qty'),
            DB::raw('SUM(scans.quantity_scanned) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as scanned_at'),
            'shipments.status'
        )
        ->groupBy('shipments.id', 'shipments.tracking_number', 'shipments.completed', 'shipments.status');

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
