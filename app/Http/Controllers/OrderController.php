<?php
namespace App\Http\Controllers;
require_once app_path('libraries/ShippingEasy.php');
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Item;
use App\Models\ItemTest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
//require_once('/home1/scanmerodomain/public_html/public/app/Libraries/ShippingEasy/ShippingEasy.php');
//require_once app_path('libraries/ShippingEasy/ShippingEasy.php');



class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Admin can see all orders; normal users only their own
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $query->where('customer_email', Auth::user()->email);
        }

        // Date range filter
        if ($request->filled('orderDateRange')) {
            $dates = explode(' - ', $request->orderDateRange);
            try {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('order_date', [$start, $end]);
            } catch (\Exception $e) {
                // Optional: flash error for invalid date format
            }
        }

        // Apply ordering before debugging SQL
        $query->orderByDesc('order_date');

        // Debug: echo full SQL query with bindings (remove this after debugging)
        //dd(vsprintf(
         //   str_replace('?', "'%s'", $query->toSql()),
         //   $query->getBindings()
        //));

        // Get paginated results (this line will not run if dd() is active)
        $orders = $query->paginate(20);

        // Choose view based on role
        $view = (Auth::user()->role === 'admin') ? 'orders.adminIndex' : 'orders.usersIndex';

        return view($view, compact('orders'));
    }
    public function show($id)
{
    $order = Order::with([
        'items.shipment',        // includes shipments for each item
        'items.scans' => function ($query) {
            $query->latest();    // optionally get latest scans
        }
    ])->findOrFail($id);

    return response()->json([
        'success' => true,
        'order' => $order
    ]);
}
public function checkTrackingNumber($tracking_number)
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

        if (isset($response['orders']) && is_array($response['orders'])) {
            foreach ($response['orders'] as $order) {
                if (!empty($order['shipments'][0])) {
                    $shipment = $order['shipments'][0];
                    if (!empty($shipment['tracking_number']) && $shipment['tracking_number'] === $tracking_number) {
                        return response()->json([
                            'success' => true,
                            'message' => '✅ Tracking number found.',
                            'tracking_number' => $shipment['tracking_number'],
                            'order_id' => $order['id'] ?? null,
                            'alternate_order_id' => $order['alternate_order_id'] ?? null,
                            'status' => $shipment['workflow_state'] ?? null
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => '❌ Tracking number not found in ShippingEasy orders.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No orders returned from ShippingEasy.'
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('ShippingEasy Check Failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'API Error: ' . $e->getMessage()
        ], 500);
    }
}


public function syncOrdersFromShippingEasy()
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

        if (isset($response['orders']) && is_array($response['orders'])) {
            $syncedOrderCount = 0;
            $syncedItemCount = 0;

            foreach ($response['orders'] as $order) {
                // Save or update Order
                $orderModel = \App\Models\Order::updateOrCreate(
                    ['shippingeasy_order_id' => $order['alternate_order_id']],
                    [
                        'customer_name' => trim(($order['billing_first_name'] ?? '') . ' ' . ($order['billing_last_name'] ?? '')),
                        'customer_email' => $order['billing_email'] ?? null,
                        'status' => $order['order_status'] ?? null,
                        'order_date' => $order['ordered_at'] ?? null,
                    ]
                );
                $syncedOrderCount++;

                // Save or update Shipment
                /*$shipment_id = null;
                if (!empty($order['shipments'][0])) {
                    $shipment = $order['shipments'][0];
                    $shipmentModel = \App\Models\Shipment::updateOrCreate(
                        ['tracking_number' => $shipment['tracking_number']],
                        [
                            'status' => $shipment['workflow_state'] ?? null,
                            'order_id' => $orderModel->id,
                        ]
                    );
                    $shipment_id = $shipmentModel->id;
                }*/
                
                if (!empty($order['shipments'][0])) {
    $shipment = $order['shipments'][0];

    // ✅ Check if tracking_number is set and not null
    if (empty($shipment['tracking_number'])) {
        \Log::warning("Skipped shipment for order {$order['alternate_order_id']} due to missing tracking number.");
        continue; // Skip this order if shipment is invalid
    }

    $shipmentModel = \App\Models\Shipment::updateOrCreate(
        ['tracking_number' => $shipment['tracking_number']],
        [
            'status' => $shipment['workflow_state'] ?? null,
            'order_id' => $orderModel->id,
        ]
    );
    $shipment_id = $shipmentModel->id;
}


                // Track unique SKUs with accumulated quantity
                $itemTracker = [];
                foreach ($order['recipients'] ?? [] as $recipient) {
                    foreach ($recipient['line_items'] ?? [] as $item) {
                        $sku = trim($item['sku'] ?? '');
                        if ($sku === '') continue;

                        $itemName = $item['item_name'] ?? '';
                        $quantity = (int) ($item['quantity'] ?? 1);

                        if (isset($itemTracker[$sku])) {
                            $itemTracker[$sku]['quantity'] += $quantity;
                        } else {
                            $itemTracker[$sku] = [
                                'barcode' => $sku,
                                'shipment_id' => $shipment_id,
                                'order_id' => $orderModel->id,
                                'name' => $itemName,
                                'quantity' => $quantity,
                            ];
                        }
                    }
                }

                // Insert or update items
                $updatedItemIds = [];
                foreach ($itemTracker as $itemData) {
                    // Extract multiplier from SKU (e.g., x10-)
                    $multiplier = 1;
                    if (preg_match('/x(\d+)-/', $itemData['barcode'], $matches)) {
                        $multiplier = (int) $matches[1];
                    }

                    $finalQuantity = $itemData['quantity'] * $multiplier;

                    $item = \App\Models\Item::updateOrCreate(
                        [
                            'barcode' => $itemData['barcode'],
                            'order_id' => $itemData['order_id'],
                        ],
                        [
                            'shipment_id' => $itemData['shipment_id'],
                            'name' => $itemData['name'],
                            'quantity' => $itemData['quantity'],
                            'required_quantity' => $finalQuantity,
                            'total_quantity' => $finalQuantity,
                            'completed' => 0,
                            'scanned_quantity' => 0,
                            'updated_at' => now(),
                        ]
                    );

                    $updatedItemIds[] = $item->id;
                    $syncedItemCount++;
                }

                // Delete stale items
                \App\Models\Item::where('order_id', $orderModel->id)
                    ->whereNotIn('id', $updatedItemIds)
                    ->delete();
            }

            // ✅ Moved OUTSIDE the loop
            return view('orders.syncdata', [
                'success' => true,
                'orderCount' => $syncedOrderCount,
                'itemCount' => $syncedItemCount
            ]);
        } else {
            return view('orders.syncdata', [
                'error' => 'No orders found in response.'
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('ShippingEasy Sync Failed: ' . $e->getMessage());
        return view('orders.syncdata', [
            'error' => $e->getMessage()
        ]);
    }
}

    
    
public function topCustomerOrders()
{
    // ðŸ‘‘ Get Top 5 Customers by number of orders
    $topCustomers = DB::table('orders')
        ->select('customer_name', DB::raw('COUNT(*) as total_orders'))
        ->groupBy('customer_name')
        ->orderByDesc('total_orders')
        ->limit(5)
        ->pluck('total_orders', 'customer_name'); // returns: ['John Doe' => 12, ...]

    return response()->json([
        'customers' => $topCustomers
    ]);
}


}
