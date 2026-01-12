<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccessControlController extends Controller
{
    public function index()
    {
        // Show all users (including dual-role and single-role)
        $users = User::orderByDesc('created_at')->get();

        return view('admin.access-control', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        User::create($validated);

        return redirect()->route('admin.access-control.index')
            ->with('success', 'Admin created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.access-control.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.access-control.index')
            ->with('success', 'Admin deleted successfully.');
    }
}

