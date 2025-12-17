<?php

use App\Models\User;
use App\Models\Review;

test('movie page shows approved reviews from other users', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    // Owner creates a collection for a TMDB id
    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 1234567,
        'title' => 'Public Movie',
    ]);

    // Create approved review on that collection
    $review = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 4,
        'review_text' => 'Solid movie',
        'is_approved' => true,
    ]);

    // Visit movie show page (guest)
    $response = $this->get(route('movies.show', $collection->tmdb_movie_id));

    $response->assertOk();
    $response->assertSee('Solid movie');
    $response->assertSee('Average Rating');
});

test('unapproved reviews do not appear on movie page', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 7654321,
        'title' => 'Hidden Movie',
    ]);

    // Unapproved review
    $review = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 1,
        'review_text' => 'Bad',
        'is_approved' => false,
    ]);

    $response = $this->get(route('movies.show', $collection->tmdb_movie_id));

    $response->assertOk();
    $response->assertDontSee('Bad');
});