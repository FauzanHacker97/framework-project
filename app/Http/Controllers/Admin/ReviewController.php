<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        
        $query = Review::with(['user', 'movieCollection']);
        
        if ($filter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($filter === 'approved') {
            $query->where('is_approved', true);
        }
        
        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews', 'filter'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'movieCollection']);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Review approved!');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Review rejected!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}