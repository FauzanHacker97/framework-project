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

test('other users can view someone else\'s collection but not owner-only details', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 999998,
        'title' => 'Another Test Movie',
        'personal_notes' => 'Secret info',
    ]);

    $response = $this
        ->actingAs($other)
        ->get(route('collections.show', $collection));

    $response->assertOk();

    // Other users should not see owner-only controls or personal notes
    $response->assertDontSee('Edit Notes');
    $response->assertDontSee('Remove from Collection');
    $response->assertDontSee('Secret info');
});
