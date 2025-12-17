<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount(['movieCollections', 'reviews'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['movieCollections', 'reviews.movieCollection']);
        
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete admin users!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}