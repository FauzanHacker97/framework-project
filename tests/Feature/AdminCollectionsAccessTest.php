<?php

use App\Models\User;
use App\Models\MovieCollection;

it('prevents admin from accessing collections index and store', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    $this->get(route('collections.index'))->assertStatus(403);

    $this->post(route('collections.store'), [
        'title' => 'Admin Attempt',
        'tmdb_movie_id' => 12345,
    ])->assertStatus(403);
});

it('prevents admin from toggling or viewing others collections', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $owner = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'user_id' => $owner->id,
        'tmdb_movie_id' => 111,
        'title' => 'Owner collection',
    ]);

    $this->actingAs($admin);

    // viewing a specific collection should be forbidden
    $this->get(route('collections.show', $collection))->assertStatus(403);

    // toggling watched should be forbidden
    $this->post(route('collections.toggle-watched', $collection))->assertStatus(403);
});

