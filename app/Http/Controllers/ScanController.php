<?php

namespace App\Http\Controllers;
require_once app_path('libraries/ShippingEasy.php');
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Shipment;
use App\Models\Scan;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



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
        return redirect()->back()->with('error', 'Shipment not found');
    }

    return view('scan.items', compact('shipment'));
}



    public function test()
    {
        // Hardcoded API Key and Secret
        $apiKey = '7b4d8c5e46f26df2de930b4264d27a13';
        $apiSecret = '5b2e656e3d23767adadb7fd09fa351a659720fb1baab2828eb67635daaa451dc';
        $apiUrl = 'https://app.shippingeasy.com/api/orders';
 
        $apiUrl = 'https://app.shippingeasy.com/api/orders';
        $timestamp = now()->toIso8601String(); // e.g., 2025-06-24T04:30:14Z
    
        $queryString = "api_key=$apiKey&timestamp=" . urlencode($timestamp);
        $stringToSign = "GET /api/orders $queryString";
    
        $signature = hash_hmac('sha256', $stringToSign, $apiSecret);
    
        $fullUrl = "$apiUrl?$queryString&signature=$signature";
    
        $response = Http::acceptJson()->get($fullUrl);
    
        if ($response->successful()) {
            return response()->json($response->json());
        }
    
        return response()->json([
            'error' => 'Failed to fetch orders',
            'status_code' => $response->status(),
            'details' => $response->body()
        ], $response->status());
    }
   

public function test1()
{


$apiKey = '7b4d8c5e46f26df2de930b4264d27a13';
$apiSecret = '5b2e656e3d23767adadb7fd09fa351a659720fb1baab2828eb67635daaa451dc';
$method = "get";
$path = "/api/orders";

$params = [
    "api_key" => $apiKey,
    "api_timestamp" => time()
];

try {
    $sear = new \ShippingEasy_ApiRequestor();
    $response = $sear->request($method, $path, $params, null, $apiKey, $apiSecret);

    // Set header to return JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (\Exception $e) {
    // Handle errors gracefully
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
    
    
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
    


public function submitItemScan(Request $request, $shipmentId)
{
    $request->validate([
        'barcode' => 'required|string'
    ]);

    $scanInput = trim($request->barcode); // user input

    $shipment = Shipment::with('items')->findOrFail($shipmentId);

    // Try to match by full or partial (endsWith) barcode
    $item = $shipment->items->first(function ($i) use ($scanInput) {
        return Str::endsWith($i->barcode, $scanInput) || $i->barcode === $scanInput;
    });

    if (!$item) {
        return redirect()->back()->with('error', 'Item with barcode not found for this shipment.');
    }

    // Prevent scanning more than required
    if ($item->scanned_quantity >= $item->required_quantity) {
        return redirect()->back()->with('error', 'All units of this item are already scanned.');
    }

    $item->scanned_quantity += 1;

    if ($item->scanned_quantity >= $item->required_quantity) {
        $item->completed = true;
    }

    $item->save();

    // Log the scan
    \App\Models\Scan::create([
        'user_id' => auth()->id(),
        'item_id' => $item->id,
        'shipment_id' => $shipment->id,
        'quantity_scanned' => 1,
        'scanned_at' => now(),
        'status' => $item->completed ? 'completed' : 'scanned',
    ]);

    // Check if all items are scanned
    $allCompleted = $shipment->items->every(fn($i) => $i->completed);

    if ($allCompleted) {
        $shipment->status = 'completed';
        $shipment->save();
        return redirect()->route('scan.label.form')->with('success', '✅ Shipment completed! Ready for next.');
    }

    return redirect()->back()->with('success', '✅ Item scanned successfully.');
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



     // Start  =   All charts for superadmin (admin) Dashboard
    // for chart Quantity Scanned by User(1)
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
//======================= Scans by Status (II) charts===========

public function getScanStatus()
{
    $statusCounts = DB::table('scans')
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    return response()->json([
        'completed' => $statusCounts->get('completed', 0),
        'scanned' => $statusCounts->get('scanned', 0),
        'failed' => $statusCounts->get('failed', 0), // optional if applicable
    ]);
}

    //====================  Scans Over Time ====================
    
    public function scanOverTime(Request $request)
    {
        // Last 30 days
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }
    
        // Query scans grouped by date
        $scans = Scan::selectRaw('DATE(scanned_at) as date, COUNT(*) as count')
            ->whereBetween('scanned_at', [
                Carbon::today()->subDays(29)->startOfDay(),
                Carbon::today()->endOfDay()
            ])
            ->groupBy('date')
            ->pluck('count', 'date'); // associative array: [ '2025-05-21' => 30 ]

            //dd($scans);

    
        // Prepare chart data
        $labels = [];
        $data = [];
    
        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->format('M d'); // 'May 21'
            $data[] = $scans->get($date, 0); // default to 0 if no data
        }
    
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
    

//====================================End Superadmin (Admin) dashboard chart ================

//====================================Start Users dashboard chart ================
public function getDashboardStatsUsers(Request $request)
{

    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Last 7 days
    $dates = collect();
    $quantities = collect();

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $dates->push($date->format('M d'));

        // Sum quantity_scanned per day
        $total = Scan::whereDate('scanned_at', $date)
            ->where('user_id', $user->id)
            ->sum('quantity_scanned');

        $quantities->push($total);
    }

    // Status counts
    $statusCounts = Scan::where('user_id', $user->id)
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

    return response()->json([
        'daily_labels' => $dates,
        'daily_data' => $quantities,
        'status_data' => $statusCounts,
    ]);
}



}
