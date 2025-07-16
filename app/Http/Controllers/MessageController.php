<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ItemTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class MessageController extends Controller
{
    public function syncOrdersFromShippingEasyTest()
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
                $shipment_id = $order['shipments'][0]['id'] ?? null;
                $order_id = $order['id'];
            
                // Clean up previous entries for same order
                ItemTest::where('order_id', $order_id)->delete();
            
                $itemTracker = []; // to avoid duplicate insert by sku
            
                foreach ($order['recipients'] ?? [] as $recipient) {
                    foreach ($recipient['line_items'] ?? [] as $item) {
                        $sku = trim($item['sku'] ?? '');
                        $itemName = $item['item_name'] ?? '';
                        $quantity = (int) ($item['quantity'] ?? 1);
            
                        // Optional: skip if already inserted
                        if (isset($itemTracker[$sku])) {
                            $itemTracker[$sku]['quantity'] += $quantity;
                            continue;
                        }
            
                        $itemTracker[$sku] = [
                            'barcode' => $sku,
                            'shipment_id' => $shipment_id,
                            'order_id' => $order_id,
                            'name' => $itemName,
                            'quantity' => $quantity,
                        ];
                    }
                }
            
                // Now insert only once per unique SKU
                foreach ($itemTracker as $barcode => $itemData) {
                    ItemTest::create([
                        'barcode' => $itemData['barcode'],
                        'shipment_id' => $itemData['shipment_id'],
                        'order_id' => $itemData['order_id'],
                        'name' => $itemData['name'],
                        'quantity' => $itemData['quantity'],
                        'required_quantity' => $itemData['quantity'],
                        'total_quantity' => $itemData['quantity'],
                        'completed' => 0,
                        'scanned_quantity' => 0,
                    ]);
                }
            }
            

            return response()->json(['message' => 'Items synced successfully.']);
        } else {
            return response()->json(['message' => 'No orders found in response.'], 404);
        }

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}

    // Return unread messages for logged-in user
    public function unreadMessages()
    {
        $user = Auth::user();
    
        $messages = Message::with('sender')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'subject' => $msg->subject,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'sender' => [
                        'id' => $msg->sender->id ?? null,
                        'name' => $msg->sender->name ?? 'Admin',
                    ],
                    'link' => route('users.messages.conversation', ['sender_id' => $msg->sender->id])
                ];
            });
    
        return response()->json([
            'count' => $messages->count(),
            'messages' => $messages
        ]);
    }
    
    // Display inbox (all received messages)
    public function userInbox()
    {
         // Mark all unread messages sent to admin as read
            Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Load messages
       $messages = Message::with('sender') // Eager load sender
        ->where('receiver_id', auth()->id()) // Only messages received by current user
        ->orderBy('created_at', 'desc')
        ->get();

    return view('users.messages.inbox', compact('messages'));
    }

    // Display conversation between the current user and a specific sender
    
    public function viewConversation($sender_id)
    {
        $userId = auth()->id();
         // Mark all unread messages sent to admin as read
         Message::where('receiver_id', $userId)
        ->where('sender_id', $sender_id) // 👈 Add this for accuracy!
        ->where('is_read', false)
        ->update(['is_read' => true]); // ✅

    
        // Fetch the sender user
        $sender = User::findOrFail($sender_id);
    
        // Get all messages between auth user and the sender
        $messages = Message::where(function ($query) use ($userId, $sender_id) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $sender_id);
            })
            ->orWhere(function ($query) use ($userId, $sender_id) {
                $query->where('sender_id', $sender_id)
                      ->where('receiver_id', $userId);
            })
            ->orderBy('created_at')
            ->get();
    
        return view('users.messages.conversation', compact('messages', 'sender'));
    }
    
    

    // AJAX endpoint to mark messages from a sender as read
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
    
            \App\Models\Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
    
            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
public function reply(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|integer|exists:users,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'parent_id' => 'nullable|exists:messages,id',
    ]);

    \App\Models\Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'subject' => $request->subject,
        'message' => $request->message,
        'is_read' => 0,
        'parent_id' => $request->parent_id, // Optional
    ]);

    return redirect()->back()->with('success', 'Reply sent successfully.');
}

}
