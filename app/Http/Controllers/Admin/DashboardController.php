<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MovieCollection;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_collections' => MovieCollection::count(),
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentReviews = Review::with(['user', 'movieCollection'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentReviews'));
    }
}