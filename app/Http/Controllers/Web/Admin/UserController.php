<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'orders' => function ($query) {
                $query->latest();
            },
            'addresses'
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Prevent deleting user with orders to maintain data integrity
        if ($user->orders()->exists()) {
            return back()->with('error', 'Cannot delete user with specific order history. Deactivate account instead.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
