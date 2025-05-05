<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlacklistedWorker;
use Illuminate\Support\Facades\Auth;

class BlacklistedWorkerController extends Controller
{
    // Show all blacklisted workers (approved ones)
    public function index()
    {
        $workers = BlacklistedWorker::where('approved', true)->paginate(10);

        return view('blacklisted.index', compact('workers'));
    }

    // Form to create a new blacklist request
    public function create()
    {
        return view('blacklisted.create');
    }

    // Store a blacklist request
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'reason' => 'required|string',
            'proof' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        BlacklistedWorker::create([
            'name' => $request->name,
            'reason' => $request->reason,
            'proof' => $request->proof,
            'email' => $request->email,
            'phone' => $request->phone,
            'reported_by' => Auth::id(), // ID of the company/member who reported
            'approved' => false, // Needs superadmin approval
        ]);

        return redirect()->back()->with('success', 'Blacklist request submitted. Waiting for Superadmin approval.');
    }

    public function edit($id)
{
    $worker = BlacklistedWorker::findOrFail($id);
    return view('blacklisted.edit', compact('worker'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'reason' => 'required|string',
        'proof' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        'reported_by' => 'required|string|max:255',
    ]);

    $worker = BlacklistedWorker::findOrFail($id);
    $worker->update($request->except('proof'));

    // Handle file upload
    if ($request->hasFile('proof')) {
        if ($worker->proof) {
            // Delete the old proof file if it exists
            Storage::delete('public/' . $worker->proof);
        }
        $worker->proof = $request->file('proof')->store('proofs', 'public');
        $worker->save();
    }

    return redirect()->route('blacklisted.index')->with('success', 'Blacklisted Worker has been updated.');
}


    // Superadmin approves a blacklisted worker
    public function approve($id)
    {
        $worker = BlacklistedWorker::findOrFail($id);
        $worker->approved = true;
        $worker->save();

        return redirect()->back()->with('success', 'Worker has been approved and blacklisted.');
    }

    public function pending()
    {
        $pendingWorkers = BlacklistedWorker::where('approved', false)->get();
        return view('blacklist.pending', compact('pendingWorkers'));
    }
    public function reject($id)
    {
        $worker = BlacklistedWorker::findOrFail($id);
        $worker->delete();

        return redirect()->back()->with('success', 'Blacklist request rejected.');
    }
}
