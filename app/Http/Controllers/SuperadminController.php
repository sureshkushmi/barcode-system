<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    
    public function index()
    {
        $members = User::where('status', 'active')->get();
        return view('superadmin.members', compact('members'));
    }
    public function pendingMembers()
    {
        $pendingMembers = User::where('role', 'member')
                                ->where('status', 'pending')
                                ->get();

        return view('superadmin.pending-members', compact('pendingMembers'));
    }

    public function approveMember($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        // Send welcome email (we'll do this in Step 4)

        return redirect()->back()->with('success', 'Member approved successfully!');
    }

    public function rejectMember($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', 'Member rejected.');
    }
}
