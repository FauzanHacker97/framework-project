<?php

use App\Models\User;

test('owner can toggle watched status', function () {
    $user = User::factory()->create();

    $collection = $user->movieCollections()->create([
        'tmdb_movie_id' => 111111,
        'title' => 'Toggle Test Movie',
        'is_watched' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('collections.toggle-watched', $collection));

    $response->assertRedirect();

    $this->assertTrue($collection->fresh()->is_watched);
});

test('other user cannot toggle someone else\'s watched status', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 222222,
        'title' => 'Toggle Forbidden Movie',
        'is_watched' => false,
    ]);

    $response = $this
        ->actingAs($other)
        ->post(route('collections.toggle-watched', $collection));

    $response->assertForbidden();

    $this->assertFalse($collection->fresh()->is_watched);
});
