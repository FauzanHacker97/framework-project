<?php

use App\Models\User;

test('owner can view their movie collection', function () {
    $user = User::factory()->create();

    $collection = $user->movieCollections()->create([
        'tmdb_movie_id' => 999999,
        'title' => 'Test Movie',
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('collections.show', $collection));

    $response->assertOk();
});

test('other users cannot view someone else\'s collection', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 999998,
        'title' => 'Another Test Movie',
    ]);

    $response = $this
        ->actingAs($other)
        ->get(route('collections.show', $collection));

    $response->assertForbidden();
});
