<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Scan; // ✅ Add this line
use App\Models\Shipment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
