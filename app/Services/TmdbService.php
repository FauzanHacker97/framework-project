<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function searchMovies($query, $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'page' => $page,
            'language' => 'en-US'
        ]);

        return $response->json();
    }

    public function getMovieDetails($movieId)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$movieId}", [
            'api_key' => $this->apiKey,
            'language' => 'en-US'
        ]);

        return $response->json();
    }

    public function getPopularMovies($page = 1)
    {
        $response = Http::get("{$this->baseUrl}/movie/popular", [
            'api_key' => $this->apiKey,
            'page' => $page,
            'language' => 'en-US'
        ]);

        return $response->json();
    }

    public function getTrendingMovies($timeWindow = 'day')
    {
        $response = Http::get("{$this->baseUrl}/trending/movie/{$timeWindow}", [
            'api_key' => $this->apiKey,
            'language' => 'en-US'
        ]);

        return $response->json();
    }

    public function getMoviesByGenre($genreId, $page = 1)
    {
        $response = Http::get("{$this->baseUrl}/discover/movie", [
            'api_key' => $this->apiKey,
            'with_genres' => $genreId,
            'page' => $page,
            'language' => 'en-US'
        ]);

        return $response->json();
    }
}