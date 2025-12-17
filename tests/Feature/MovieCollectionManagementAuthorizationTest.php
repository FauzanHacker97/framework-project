<?php

use App\Models\User;

test('owner can view edit page and update collection', function () {
    $user = User::factory()->create();

    $collection = $user->movieCollections()->create([
        'tmdb_movie_id' => 333333,
        'title' => 'Manage Test Movie',
        'is_watched' => false,
        'personal_notes' => null,
    ]);

    // Edit page
    $response = $this
        ->actingAs($user)
        ->get(route('collections.edit', $collection));

    $response->assertOk();

    // Update
    $response = $this
        ->actingAs($user)
        ->from(route('collections.edit', $collection))
        ->patch(route('collections.update', $collection), [
            'is_watched' => true,
            'personal_notes' => 'Updated notes',
        ]);

    $response->assertRedirect(route('collections.show', $collection));

    $collection->refresh();

    $this->assertTrue($collection->is_watched);
    $this->assertSame('Updated notes', $collection->personal_notes);
});

test('other users cannot access edit or update someone else\'s collection', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 444444,
        'title' => 'Manage Forbidden Movie',
    ]);

    $response = $this
        ->actingAs($other)
        ->get(route('collections.edit', $collection));

    $response->assertForbidden();

    $response = $this
        ->actingAs($other)
        ->patch(route('collections.update', $collection), [
            'is_watched' => true,
        ]);

    $response->assertForbidden();
});

test('owner can delete their collection', function () {
    $user = User::factory()->create();

    $collection = $user->movieCollections()->create([
        'tmdb_movie_id' => 555555,
        'title' => 'Delete Test Movie',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('collections.show', $collection))
        ->delete(route('collections.destroy', $collection));

    $response->assertRedirect(route('collections.index'));

    $this->assertNull($collection->fresh());
});

test('other users cannot delete someone else\'s collection', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $collection = $owner->movieCollections()->create([
        'tmdb_movie_id' => 666666,
        'title' => 'Delete Forbidden Movie',
    ]);

    $response = $this
        ->actingAs($other)
        ->delete(route('collections.destroy', $collection));

    $response->assertForbidden();

    $this->assertNotNull($collection->fresh());
});
