<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

class MessageController extends Controller
{
     /**
     * Display a listing of the sent messages.
     */
    public function index()
{
   $userId = auth()->id();

    // Only top-level (original) messages sent by admin (not replies)
    $messages = Message::where('sender_id', $userId)
        ->whereNull('parent_id')
        ->select('subject', 'message', 'created_at')
        ->groupBy('subject', 'message', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(10); // ✅ paginate instead of get()

   return view('superadmin.messages.index', compact('messages'));
}

    public function inbox()
    {
        
    // Mark all unread messages sent to admin as read
    Message::where('receiver_id', auth()->id())
    ->where('is_read', false)
    ->update(['is_read' => true]);

// Load messages
        $messages = Message::where('receiver_id', auth()->id())  // Only messages sent to admin
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

    return view('superadmin.messages.inbox', compact('messages'));

    }

    /**
     * Show a single message detail.
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('superadmin.messages.show', compact('message'));
    }
/**
     * Show the form for creating a new message.
     */
    public function create()
    {
        $users = User::where('role', 'users')->get(); // Only normal users
    return view('superadmin.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
    
        $adminId = auth()->id();
    
        // Get all users except the admin
        $users = \App\Models\User::where('id', '!=', $adminId)->pluck('id');
    
        foreach ($users as $userId) {
            \App\Models\Message::create([
                'sender_id' => $adminId,
                'receiver_id' => $userId,
                'subject' => $request->subject,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }
    
       return redirect()->route('superadmin.messages.index')->with('success', 'Message sent!');
    }


public function reply(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|integer|exists:users,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'parent_id' => 'nullable|exists:messages,id'
    ]);

    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'subject' => $request->subject,
        'message' => $request->message,
        'parent_id' => $request->parent_id,
        'is_read' => 0,
    ]);

    return redirect()->back()->with('success', 'Reply sent successfully.');
}

public function fetchReplies($id)
{
    $replies = Message::where('parent_id', $id)
        ->with('sender')
        ->orderBy('created_at')
        ->get()
        ->map(function ($reply) {
            return [
                'sender_name' => $reply->sender->name ?? 'Unknown',
                'message' => $reply->message,
                'time' => $reply->created_at->diffForHumans(),
            ];
        });

    return response()->json(['replies' => $replies]);
}

    }
