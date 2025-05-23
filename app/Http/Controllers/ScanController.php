<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Shipment;



class ScanController extends Controller
{
    public function scanLabelForm()
    {
        return view('scan.label');
    }

    public function handleLabel(Request $request)
{
    $request->validate([
        'tracking_number' => 'required|string'
    ]);

    $shipment = Shipment::where('tracking_number', $request->tracking_number)->first();

    if (!$shipment) {
        return redirect()->back()->withErrors(['tracking_number' => 'Shipment not found']);
    }

    return redirect()->route('scan.items', $shipment->id);
}


    public function scanItemForm()
    {
        return view('scan.items');
    }
    public function scanItems($shipmentId)
    {
        $shipment = Shipment::with('items')->findOrFail($shipmentId);
        return view('scan.items', compact('shipment'));
    }

    
    public function updateItem($itemId)
    {
        // Find the item, not shipment
        $item = Item::findOrFail($itemId);
    
        // Defensive check for shipment relation
        $shipment = $item->shipment;
        if (!$shipment) {
            return redirect()->back()->with('error', 'Shipment not found for this item.');
        }
    
        $item->scanned_quantity = ($item->scanned_quantity ?? 0) + 1;
    
        if ($item->scanned_quantity >= $item->required_quantity) {
            $item->completed = true;
        }
    
        $item->save();
    
        $allCompleted = $shipment->items->every(fn($i) => $i->completed);
    
        if ($allCompleted) {
            return redirect()->route('scan.label.form')->with('success', 'Shipment completed! Ready for next.');
        }
    
        return redirect()->back()->with('success', 'Item scanned.');
    }
    

    
    

    public function nextShipment()
    {
        // Find the next shipment that is not completed
    $nextShipment = Shipment::where('status', '!=', 'completed')->orderBy('id')->first();

    if ($nextShipment) {
        return redirect()->route('scan.items', $nextShipment->id);
    } else {
        return redirect()->route('scan.label.form')->with('success', 'All shipments are completed!');
    }
    }

    // for chart Quantity Scanned by User
    public function getUserScanStats(Request $request)
{
    $filter = $request->input('filter', 'week'); // default to week
    $dateRange = $request->input('date_range');

    $query = DB::table('scans')
        ->join('users', 'scans.user_id', '=', 'users.id')
        ->select('users.id', 'users.name', DB::raw('SUM(scans.quantity_scanned) as total_scanned'))
        ->groupBy('users.id', 'users.name');

    if ($filter === 'day') {
        $query->whereDate('scans.scanned_at', today());
    } elseif ($filter === 'week') {
        $query->whereBetween('scans.scanned_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($filter === 'custom' && $dateRange) {
        [$start, $end] = explode(' - ', $dateRange);
        $query->whereBetween('scans.scanned_at', [trim($start), trim($end)]);
    }

    return response()->json($query->get());
}

public function getScanStatus()
    {
        // Assuming you have a `scans` table with a `status` column
        $statusCounts = DB::table('shipments')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return response()->json([
            'delivered' => $statusCounts->get('delivered', 0),
            'pending' => $statusCounts->get('pending', 0),
            'shipped' => $statusCounts->get('shipped', 0),
            'failed' => $statusCounts->get('Failed', 0),
        ]);
    }

    public function weeklyScans()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Format: ['2025-05-17' => 10, '2025-05-18' => 5, ...]
        $scans = DB::table('items')
            ->select(DB::raw("DATE(created_at) as day"), DB::raw("count(*) as total"))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $days = [];
        $counts = [];

        for ($i = 0; $i <= 6; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $days[] = Carbon::parse($date)->format('D'); // Mon, Tue, etc.
            $counts[] = $scans->get($date, 0);
        }

        return response()->json([
            'labels' => $days,
            'data' => $counts
        ]);
    }



public function getScanSummary(Request $request)
{
    $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(7);
    $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

    // Completed Scans count (sum quantity_scanned where status completed)
    $completedScans = \DB::table('scans')
        ->whereBetween('scanned_at', [$start, $end])
        ->where('status', 'completed')
        ->sum('quantity_scanned');

    // Pending Scans count (sum quantity_scanned where status pending)
    $pendingScans = \DB::table('scans')
        ->whereBetween('scanned_at', [$start, $end])
        ->where('status', 'pending')
        ->sum('quantity_scanned');

    // Last scan date
    $lastScanDate = \DB::table('scans')
        ->whereBetween('scanned_at', [$start, $end])
        ->orderBy('scanned_at', 'desc')
        ->value('scanned_at');

    return response()->json([
        'completed' => $completedScans,
        'pending' => $pendingScans,
        'lastScanDate' => $lastScanDate ? Carbon::parse($lastScanDate)->format('M d, Y H:i') : 'No scans',
    ]);
}



//===============================Four Users Dashboard =========================
//total scans this week 
public function totalScansThisWeekView()
{
    $startOfWeek = now()->startOfWeek();
    $endOfWeek = now()->endOfWeek();

    $userId = Auth::id(); // get current logged-in user

    $totalScansThisWeek = DB::table('scans')
        ->where('user_id', $userId)
        ->whereBetween('scanned_at', [$startOfWeek, $endOfWeek])
        ->sum('quantity_scanned');

    return view('users.dashboard', compact('totalScansThisWeek'));
}


}
