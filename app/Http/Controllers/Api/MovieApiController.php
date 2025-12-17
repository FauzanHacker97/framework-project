<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MovieCollection;
use App\Models\Review;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        $movies = $this->tmdbService->searchMovies($query);
        
        return response()->json($movies);
    }

    public function popular()
    {
        $movies = $this->tmdbService->getPopularMovies();
        
        return response()->json($movies);
    }

    public function show($id)
    {
        $movie = $this->tmdbService->getMovieDetails($id);
        
        return response()->json($movie);
    }

    public function userCollections()
    {
        $collections = auth()->user()->movieCollections()
            ->with('reviews')
            ->latest()
            ->get();

        return response()->json($collections);
    }

    public function addToCollection(Request $request)
    {
        $validated = $request->validate([
            'tmdb_movie_id' => 'required|integer',
        ]);

        $movieData = $this->tmdbService->getMovieDetails($validated['tmdb_movie_id']);

        $collection = auth()->user()->movieCollections()->create([
            'tmdb_movie_id' => $movieData['id'],
            'title' => $movieData['title'],
            'poster_path' => $movieData['poster_path'] ?? null,
            'backdrop_path' => $movieData['backdrop_path'] ?? null,
            'overview' => $movieData['overview'] ?? null,
            'release_date' => $movieData['release_date'] ?? null,
            'vote_average' => $movieData['vote_average'] ?? null,
        ]);

        return response()->json($collection, 201);
    }

    public function removeFromCollection($id)
    {
        $collection = auth()->user()->movieCollections()->findOrFail($id);
        $collection->delete();

        return response()->json(['message' => 'Removed from collection'], 200);
    }
}