<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieCollectionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Movies Routes (Public)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/trending', [MovieController::class, 'trending'])->name('movies.trending');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Movie Collections
    Route::resource('collections', MovieCollectionController::class);
    Route::post('collections/{movieCollection}/toggle-watched', [MovieCollectionController::class, 'toggleWatched'])
        ->name('collections.toggle-watched');

    // Reviews
    Route::get('collections/{movieCollection}/reviews/create', [ReviewController::class, 'create'])
        ->name('reviews.create');
    Route::post('collections/{movieCollection}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
    Route::resource('reviews', ReviewController::class)->except(['create', 'store']);
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);
    
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
});

require __DIR__.'/auth.php';