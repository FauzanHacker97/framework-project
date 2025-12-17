<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\MovieCollection;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->reviews()->with('movieCollection')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function create(MovieCollection $movieCollection)
    {
        // Check authorization
        if ($movieCollection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if user already reviewed this movie
        $existingReview = $movieCollection->reviews()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->route('collections.show', $movieCollection)
                ->with('error', 'You have already reviewed this movie!');
        }

        return view('reviews.create', compact('movieCollection'));
    }

    public function store(Request $request, MovieCollection $movieCollection)
    {
        // Check authorization
        if ($movieCollection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10|max:1000',
        ]);

        $movieCollection->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
        ]);

        return redirect()->route('collections.show', $movieCollection)
            ->with('success', 'Review added successfully!');
    }

    public function edit(Review $review)
    {
        // Check authorization
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Check authorization
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('collections.show', $review->movieCollection)
            ->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        // Check authorization
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}