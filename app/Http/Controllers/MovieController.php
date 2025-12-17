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
        $movie = $this->tmdbService->getMovieDetails($id);
        
        // Check if user already has this in collection
        $inCollection = auth()->check() 
            ? auth()->user()->movieCollections()->where('tmdb_movie_id', $id)->exists()
            : false;

        return view('movies.show', compact('movie', 'inCollection'));
    }

    public function trending()
    {
        $movies = $this->tmdbService->getTrendingMovies();
        return view('movies.trending', compact('movies'));
    }
}