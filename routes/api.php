<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes
Route::get('/movies/search', [MovieApiController::class, 'search']);
Route::get('/movies/popular', [MovieApiController::class, 'popular']);
Route::get('/movies/{id}', [MovieApiController::class, 'show']);

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/collections', [MovieApiController::class, 'userCollections']);
    Route::post('/user/collections', [MovieApiController::class, 'addToCollection']);
    Route::delete('/user/collections/{id}', [MovieApiController::class, 'removeFromCollection']);
});