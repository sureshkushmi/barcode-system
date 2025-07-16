<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;       // ✅ DB facade
use Carbon\Carbon;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Scans;
use App\Exports\UserScanningExport;
use App\Exports\UsersExport;
use App\Exports\SuperadminUserScanningExport;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
        public function allUsersScanning(Request $request)
{
// Query to show each item as a row
    $query = DB::table('items')
        ->leftJoin('orders', 'items.order_id', '=', 'orders.id')
        ->leftJoin('shipments', 'items.shipment_id', '=', 'shipments.id')
        ->leftJoin('scans', 'items.id', '=', 'scans.item_id')
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            'items.barcode',
            'items.required_quantity',
            'orders.id as order_id',
            'orders.shippingeasy_order_id',
            'orders.customer_name',
            'orders.order_date',
            'orders.status',
            
            'shipments.tracking_number',
            DB::raw('COALESCE(SUM(scans.quantity_scanned), 0) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as last_scanned_at')
        )
        ->groupBy(
            'items.id',
            'items.name',
            'items.barcode',
            'items.required_quantity',
            'orders.id',
            'orders.customer_name',
            'shipments.tracking_number'
        )
        ->orderByDesc('last_scanned_at');

    // Optional date filter
    if ($request->filled('orderDateRange')) {
            $dates = explode(' - ', $request->orderDateRange);
            try {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('scans.scanned_at', [$start, $end]);
            } catch (\Exception $e) {
                // Optional: flash error for invalid date format
            }
        }
        
  // for users filter
if ($request->filled('user_id')) {
    $query->where('scans.user_id', $request->user_id);
}

    $items = $query->paginate(10);

    // Fetch scan details for each item (for modals)
    $itemIds = $items->pluck('item_id');

    $scanDetails = DB::table('scans')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->whereIn('scans.item_id', $itemIds)
        ->select(
            'scans.item_id',
            'users.name as scanned_by',
            'scans.quantity_scanned',
            'scans.scanned_at'
        )
        ->orderBy('scans.scanned_at')
        ->get()
        ->groupBy('item_id');



    return view('superadmin.reports',compact('items', 'scanDetails'));
}


public function relatedUsersScanning(Request $request, $userId)
{
    $query = DB::table('items')
        ->leftJoin('orders', 'items.order_id', '=', 'orders.id')
        ->leftJoin('shipments', 'items.shipment_id', '=', 'shipments.id')
        ->leftJoin('scans', 'items.id', '=', 'scans.item_id')
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            'items.barcode',
            'items.required_quantity',
            'orders.id as order_id',
            'orders.shippingeasy_order_id',
            'orders.customer_name',
            'orders.order_date',
            'orders.status',
            'shipments.tracking_number',
            DB::raw('COALESCE(SUM(scans.quantity_scanned), 0) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as last_scanned_at')
        )
        ->where('scans.user_id', $userId)
        ->groupBy(
            'items.id',
            'items.name',
            'items.barcode',
            'items.required_quantity',
            'orders.id',
            'orders.customer_name',
            'orders.shippingeasy_order_id',
            'orders.order_date',
            'orders.status',
            'shipments.tracking_number'
        )
        ->orderByDesc('last_scanned_at');

    if ($request->filled('orderDateRange')) {
        try {
            [$start, $end] = explode(' - ', $request->orderDateRange);
            $query->whereBetween('scans.scanned_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        } catch (\Exception $e) {
            // Handle invalid date
        }
    }

    $items = $query->paginate(10);
    $itemIds = $items->pluck('item_id');

    $scanDetails = DB::table('scans')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->whereIn('scans.item_id', $itemIds)
        ->select(
            'scans.item_id',
            'users.name as scanned_by',
            'scans.quantity_scanned',
            'scans.scanned_at'
        )
        ->orderBy('scans.scanned_at')
        ->get()
        ->groupBy('item_id');

    return view('superadmin.relatedUsersReports', compact('items', 'scanDetails'));
}



public function usersScanningReport(Request $request)
{
   $query = DB::table('items')
        ->leftJoin('orders', 'items.order_id', '=', 'orders.id')
        ->leftJoin('shipments', 'items.shipment_id', '=', 'shipments.id')
        ->leftJoin('scans', 'items.id', '=', 'scans.item_id')
        ->leftJoin('users', 'scans.user_id', '=', 'users.id')
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            'items.barcode',
            'items.required_quantity',
            'orders.id as order_id',
            'orders.shippingeasy_order_id',
            'orders.customer_name',
            'orders.order_date',
            'orders.status',
            'shipments.tracking_number',
            DB::raw('COALESCE(SUM(scans.quantity_scanned), 0) as scanned_qty'),
            DB::raw('MAX(scans.scanned_at) as last_scanned_at')
        )
        ->groupBy(
            'items.id',
            'items.name',
            'items.barcode',
            'items.required_quantity',
            'orders.id',
            'orders.customer_name',
            'orders.order_date',
            'orders.status',
            'orders.shippingeasy_order_id',
            'shipments.tracking_number'
        )
        ->orderByDesc('last_scanned_at');

    // ✅ Optional user filter
    if ($request->filled('user_id')) {
        $query->where('scans.user_id', $request->user_id);
    }

    $items = $query->paginate(10);

    // Fetch scan details for each item (for modals)
    $itemIds = $items->pluck('item_id');

    $scanDetails = DB::table('scans')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->whereIn('scans.item_id', $itemIds)
        ->select(
            'scans.item_id',
            'users.name as scanned_by',
            'scans.quantity_scanned',
            'scans.scanned_at'
        )
        ->orderBy('scans.scanned_at')
        ->get()
        ->groupBy('item_id');

    // ✅ Get users list for dropdown
    $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

    return view('users.users_scanning_reports', compact('items', 'scanDetails', 'users'));

   // return view('users.users_scanning_reports', compact('shipments', 'scannedItems', 'users'));
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
    ->join('users', 'scans.user_id', '=', 'users.id')
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

// Initialize empty collections for missing shipment IDs
foreach ($shipmentIds as $id) {
    if (!isset($scannedItems[$id])) {
        $scannedItems[$id] = collect(); // empty collection
    }
}

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
