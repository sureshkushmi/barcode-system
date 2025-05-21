<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\User;

class SuperadminController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::where('status', 'active')->where('role', 'users')->get();
        return view('superadmin.dashboard', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('superadmin.create');
    }

    // Store new user
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'users',
            'status' => 'active'
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User added successfully');
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'nullable|string|in:users,admin',
            'status'   => 'nullable|string|in:active,inactive',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'] ?? 'users';
        $user->status = $validated['status'] ?? 'active';

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.users')->with('success', 'User deleted successfully.');
    }
}
