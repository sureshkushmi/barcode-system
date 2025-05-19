<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Scan; // ✅ Add this line
use App\Models\Shipment;
use App\Models\Item;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function dashboard()
    {
        // Get user information (example)
        $user = auth()->user();
        return view('users.dashboard', compact('user'));
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
