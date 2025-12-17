<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  // ← MAKE SURE THIS IS HERE
        'tmdb_movie_id',
        'title',
        'poster_path',
        'backdrop_path',
        'overview',
        'release_date',
        'vote_average',
        'is_watched',
        'personal_notes',
    ];

    protected $casts = [
        'is_watched' => 'boolean',
        'vote_average' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPosterUrlAttribute()
    {
        return $this->poster_path 
            ? "https://image.tmdb.org/t/p/w500{$this->poster_path}" 
            : asset('images/no-poster.jpg');
    }

    public function getBackdropUrlAttribute()
    {
        return $this->backdrop_path 
            ? "https://image.tmdb.org/t/p/original{$this->backdrop_path}" 
            : null;
    }
}