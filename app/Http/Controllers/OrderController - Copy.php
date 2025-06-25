<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
require_once app_path('Libraries/ShippingEasy/ShippingEasy.php');




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

    public function getOrder()
    {
        $apiKey = '7b4d8c5e46f26df2de930b4264d27a13';
        $apiSecret = '5b2e656e3d23767adadb7fd09fa351a659720fb1baab2828eb67635daaa451dc';
    
        $method = "get";
        $path = "/api/orders";
        $params = [
            "api_key" =>  $apiKey,
            "api_timestamp" => time()
        ];
        $json_body = null;
    
        try {
            $sear = new \ShippingEasy_ApiRequestor();

            $res = $sear->request($method, $path, $params, $json_body, $apiKey, $apiSecret);
            return response()->json($res);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}
