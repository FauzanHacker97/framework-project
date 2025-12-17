<?php

namespace App\Policies;

use App\Models\MovieCollection;
use App\Models\User;

class MovieCollectionPolicy
{
    public function view(User $user, MovieCollection $movieCollection): bool
    {
        return $user->id === $movieCollection->user_id;
    }

    public function update(User $user, MovieCollection $movieCollection): bool
    {
        return $user->id === $movieCollection->user_id;
    }

    public function delete(User $user, MovieCollection $movieCollection): bool
    {
        return $user->id === $movieCollection->user_id;
    }
}