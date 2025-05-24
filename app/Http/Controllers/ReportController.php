<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Scans;
 use Illuminate\Support\Facades\DB;


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
        ->leftJoin('scans', function ($join) use ($userId) {
            $join->on('shipments.id', '=', 'scans.shipment_id')
                 ->where('scans.user_id', $userId); // Filter by current user
        })
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


}
