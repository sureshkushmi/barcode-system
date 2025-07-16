<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Scan; // ✅ Add this line
use App\Models\Shipment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    

public function dashboard()
{
    $userId = auth()->id(); // current user
    $user = User::find($userId);

    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    // Total scans this week by user
    $totalScansThisWeek = Scan::where('user_id', $userId)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->count();

    // Completed scans (status = 'completed' or whatever value you use)
    $completedScans = Scan::where('user_id', $userId)
        ->where('status', 'completed')
        ->count();

    // Pending scans (status = 'pending' or similar)
    $pendingScans = Scan::where('user_id', $userId)
        ->where('status', 'pending')
        ->count();

    // Last scan date
    $lastScanDate = Scan::where('user_id', $userId)
        ->latest('created_at')
        ->value('created_at');

    return view('users.dashboard', compact(
        'user',
        'totalScansThisWeek',
        'completedScans',
        'pendingScans',
        'lastScanDate'
    ));
}

   

       // Entry point to the barcode scanner
       public function scanner()
       {
           return view('user.scanner');
       }
   
       // Show user's scan history / reports
            public function userReports()
        {
            $user = auth()->user();

            // Load scans with shipment and item info
            $scans = Scan::with(['shipment', 'item'])
                ->where('user_id', $user->id)
                ->orderBy('scanned_at', 'desc')
                ->paginate(15);

            return view('users.reports', compact('scans'));
        }
        
         /** Display for scan order list per users */
        public function userScannedOrders(Request $request)
        {
            $userId = auth()->id();

            $query = DB::table('shipments')
                ->join('scans', function ($join) use ($userId) {
                    $join->on('shipments.id', '=', 'scans.shipment_id')
                        ->where('scans.user_id', $userId);
                })
                ->leftJoin('items', 'scans.item_id', '=', 'items.id')
                ->select(
                    'shipments.id as shipment_id',
                    'shipments.tracking_number',
                    'shipments.status as shipment_status',
                    'shipments.created_at as shipment_created_at',
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
                    'items.required_quantity'
                )
                ->orderByDesc('scanned_at');

            // Optional date filter
            if ($request->filled('datetimes')) {
                [$start, $end] = explode(' - ', $request->input('datetimes'));
                $start = Carbon::parse($start)->startOfDay();
                $end = Carbon::parse($end)->endOfDay();
                $query->whereBetween('scans.scanned_at', [$start, $end]);
            }

            $shipments = $query->paginate(10);

            // Fetch scanned items per shipment
            $shipmentIds = $shipments->pluck('shipment_id');
            $scannedItems = DB::table('scans')
                ->join('items', 'scans.item_id', '=', 'items.id')
                ->join('users', 'scans.user_id', '=', 'users.id')
                ->whereIn('scans.shipment_id', $shipmentIds)
                ->where('scans.user_id', $userId)
                ->select(
                    'scans.shipment_id',
                    'items.name as item_name',
                    'items.barcode',
                    'items.required_quantity as total_quantity',
                    'scans.quantity_scanned',
                    'users.name as scanned_by',
                    'scans.scanned_at'
                )
                ->orderBy('scans.scanned_at', 'desc')
                ->get()
                ->groupBy('shipment_id');

            foreach ($shipmentIds as $id) {
                if (!isset($scannedItems[$id])) {
                    $scannedItems[$id] = collect(); // ensure every shipment has collection
                }
             }

            return view('scan.users_scanned_orders', compact('shipments', 'scannedItems'));
        }
        
        public function getShipmentDetails($shipmentId)
{
    $userId = auth()->id();

    $shipment = DB::table('shipments')
        ->where('id', $shipmentId)
        ->first();

    if (!$shipment) {
        return response()->json(['success' => false, 'message' => 'Shipment not found']);
    }

    $items = DB::table('scans')
        ->join('items', 'scans.item_id', '=', 'items.id')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->where('scans.shipment_id', $shipmentId)
        ->where('scans.user_id', $userId)
        ->select(
            'items.name as item_name',
            'items.barcode',
            'items.required_quantity as total_quantity',
            'scans.quantity_scanned',
            'users.name as scanned_by',
            'scans.scanned_at'
        )
        ->orderBy('scans.scanned_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'shipment' => $shipment,
        'items' => $items
    ]);
}

        
        public function userPendingOrders(Request $request)
{
    $userId = auth()->id();

    // Cache pending order count for 5 minutes
    $pendingOrderCount = Cache::remember("user:{$userId}:pending_orders_count", now()->addMinutes(5), function () use ($userId) {
        return DB::table('orders')
            ->join('items', 'orders.id', '=', 'items.order_id')
            ->leftJoin('scans', function ($join) use ($userId) {
                $join->on('items.id', '=', 'scans.item_id')
                     ->where('scans.user_id', '=', $userId);
            })
            ->select('orders.id')
            ->groupBy('orders.id')
            ->havingRaw('COALESCE(SUM(scans.quantity_scanned), 0) < SUM(items.required_quantity)')
            ->count();
    });

    // Pending orders query
    $pendingOrders = DB::table('orders')
        ->join('items', 'orders.id', '=', 'items.order_id')
        ->leftJoin('scans', function ($join) use ($userId) {
            $join->on('items.id', '=', 'scans.item_id')
                 ->where('scans.user_id', '=', $userId);
        })
        ->leftJoin('shipments', 'items.shipment_id', '=', 'shipments.id')
        ->select(
            'orders.id as order_id',
            'orders.shippingeasy_order_id',
            'orders.customer_name',
            'orders.order_date',
            'shipments.tracking_number',
            DB::raw('SUM(items.required_quantity) as total_required'),
            DB::raw('COALESCE(SUM(scans.quantity_scanned), 0) as user_scanned_qty')
        )
        ->groupBy(
            'orders.id',
            'orders.shippingeasy_order_id',
            'orders.customer_name',
            'orders.order_date',
            'shipments.tracking_number'
        )
        ->havingRaw('user_scanned_qty < total_required')
        ->orderByDesc('orders.order_date')
        ->paginate(10);

    // Scan details for modals
    $orderIds = $pendingOrders->pluck('order_id');
    $scanDetails = DB::table('scans')
        ->join('items', 'scans.item_id', '=', 'items.id')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->whereIn('items.order_id', $orderIds)
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            'items.barcode',
            'items.order_id',
            'scans.quantity_scanned',
            'scans.scanned_at',
            'users.name as scanned_by',
            'items.shipment_id'
        )
        ->get()
        ->groupBy('item_id');

    return view('users.pending-orders', compact('pendingOrders', 'pendingOrderCount', 'scanDetails'));
}

public function getScanReport($id)
{
    $today = Carbon::today();
    $weekStart = Carbon::now()->startOfWeek();
    $weekEnd = Carbon::now()->endOfWeek();

    $scansToday = Scan::where('user_id', $id)
        ->whereDate('scanned_at', $today)
        ->count();

    $scansWeek = Scan::where('user_id', $id)
        ->whereBetween('scanned_at', [$weekStart, $weekEnd])
        ->count();

    $scansTotal = Scan::where('user_id', $id)->count();

    // ✅ Get scan history details with order & item info
   $scanDetails = DB::table('scans')
    ->join('items', 'scans.item_id', '=', 'items.id')
    ->leftJoin('orders', 'items.order_id', '=', 'orders.id')
    ->join('shipments', 'scans.shipment_id', '=', 'shipments.id')
    ->where('scans.user_id', $id)
    ->select(
        'items.name as item_name',
        'items.barcode',
        'orders.shippingeasy_order_id',
        'orders.customer_name',
        'scans.quantity_scanned',
        'scans.scanned_at',
        'shipments.tracking_number'
    )
    ->orderByDesc('scans.scanned_at')
    ->limit(10)
    ->get();


    return response()->json([
        'success' => true,
        'data' => [
            'scansToday' => $scansToday,
            'scansWeek' => $scansWeek,
            'scansTotal' => $scansTotal,
            'details' => $scanDetails,
        ]
    ]);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::where('status', 'active')->get();
        return view('members.members', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
