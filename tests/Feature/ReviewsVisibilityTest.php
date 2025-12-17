<?php

use App\Models\User;
use App\Models\Review;

test('non-owner sees only approved reviews and not personal notes', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 777777,
        'title' => 'Visibility Movie',
        'personal_notes' => 'Secret notes',
    ]);

    // Create reviews directly in DB (simulate other users reviewing)
    $approved = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 5,
        'review_text' => 'Great movie!',
        'is_approved' => true,
    ]);

    $unapproved = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 1,
        'review_text' => 'Terrible movie...',
        'is_approved' => false,
    ]);

    $response = $this
        ->actingAs($other)
        ->get(route('collections.show', $collection));

    $response->assertOk();
    $response->assertSee('Great movie!');
    $response->assertDontSee('Terrible movie...');

    // Personal notes are private
    $response->assertDontSee('Secret notes');
});

test('owner sees all reviews and their personal notes', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 888888,
        'title' => 'Owner Visibility Movie',
        'personal_notes' => 'Owner secret',
    ]);

    $approved = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 5,
        'review_text' => 'Loved it!',
        'is_approved' => true,
    ]);

    $unapproved = Review::create([
        'user_id' => $other->id,
        'movie_collection_id' => $collection->id,
        'rating' => 2,
        'review_text' => 'Not great',
        'is_approved' => false,
    ]);

    $response = $this
        ->actingAs($owner)
        ->get(route('collections.show', $collection));

    $response->assertOk();
    $response->assertSee('Loved it!');
    $response->assertSee('Not great');
    $response->assertSee('Owner secret');
});