<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
        
        if ($query) {
            $movies = $this->tmdbService->searchMovies($query);
        } else {
            $movies = $this->tmdbService->getPopularMovies();
        }

        return view('movies.index', compact('movies', 'query'));
    }

    public function show($id)
    {
        $movie = $this->tmdbService->getMovieDetails($id) ?? [];
        
        // Check if user already has this in collection
        $inCollection = auth()->check() 
            ? auth()->user()->movieCollections()->where('tmdb_movie_id', $id)->exists()
            : false;

        // Gather approved reviews for this TMDB movie across collections
        $reviews = \App\Models\Review::whereHas('movieCollection', function ($q) use ($id) {
            $q->where('tmdb_movie_id', $id);
        })->where('is_approved', true)
          ->with('user')
          ->latest()
          ->get();

        // Calculate average rating if any
        $averageRating = $reviews->count() ? round($reviews->avg('rating'), 1) : null;

        return view('movies.show', compact('movie', 'inCollection', 'reviews', 'averageRating'));
    }

    public function trending()
    {
        $movies = $this->tmdbService->getTrendingMovies();
        return view('movies.trending', compact('movies'));
    }
}