<?php

namespace App\Http\Controllers;

use App\Models\MovieCollection;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieCollectionController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        
        $query = auth()->user()->movieCollections()->with('reviews');
        
        if ($filter === 'watched') {
            $query->where('is_watched', true);
        } elseif ($filter === 'unwatched') {
            $query->where('is_watched', false);
        }
        
        $collections = $query->latest()->paginate(12);
        
        $stats = [
            'total' => auth()->user()->movieCollections()->count(),
            'watched' => auth()->user()->movieCollections()->where('is_watched', true)->count(),
            'unwatched' => auth()->user()->movieCollections()->where('is_watched', false)->count(),
            'reviews' => auth()->user()->reviews()->count(),
        ];

        return view('collections.index', compact('collections', 'filter', 'stats'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'tmdb_movie_id' => 'required|integer',
    ]);

    // Check if already in collection
    $exists = auth()->user()->movieCollections()
        ->where('tmdb_movie_id', $validated['tmdb_movie_id'])
        ->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'Movie already in your collection!');
    }

    // Get movie details from TMDB
    $movieData = $this->tmdbService->getMovieDetails($validated['tmdb_movie_id']);

   // Add to collection - EXPLICITLY SET user_id
$collection = MovieCollection::create([
    'user_id' => auth()->id(),  // ← ADD THIS LINE
    'tmdb_movie_id' => $movieData['id'],
    'title' => $movieData['title'],
    'poster_path' => $movieData['poster_path'] ?? null,
    'backdrop_path' => $movieData['backdrop_path'] ?? null,
    'overview' => $movieData['overview'] ?? null,
    'release_date' => $movieData['release_date'] ?? null,
    'vote_average' => $movieData['vote_average'] ?? null,
]);

    return redirect()->route('collections.index')->with('success', 'Movie added to your collection!');
}

   public function show(MovieCollection $movieCollection)
{
    // Simple type-safe check
    if ((int)$movieCollection->user_id !== (int)auth()->id()) {
        abort(403, 'Unauthorized - This is not your collection');
    }
    
    $movieCollection->load('reviews.user');
    
    return view('collections.show', compact('movieCollection'));
}

    public function edit(MovieCollection $movieCollection)
    {
        // Simple type-safe check
        if ((int)$movieCollection->user_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('collections.edit', compact('movieCollection'));
    }

    public function update(Request $request, MovieCollection $movieCollection)
    {
        // Simple type-safe check
        if ((int)$movieCollection->user_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'is_watched' => 'nullable|boolean',
            'personal_notes' => 'nullable|string|max:1000',
        ]);

        // Handle checkbox - if not present, it means unchecked
        $validated['is_watched'] = $request->has('is_watched') ? true : false;

        $movieCollection->update($validated);

        return redirect()->route('collections.show', $movieCollection)
            ->with('success', 'Collection updated successfully!');
    }

    public function destroy(MovieCollection $movieCollection)
    {
        // Simple type-safe check
        if ((int)$movieCollection->user_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $movieCollection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Movie removed from collection!');
    }

    public function toggleWatched(MovieCollection $movieCollection)
    {
        // Simple type-safe check
        if ((int)$movieCollection->user_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $movieCollection->update([
            'is_watched' => !$movieCollection->is_watched
        ]);

        return redirect()->back()->with('success', 'Watch status updated!');
    }
}