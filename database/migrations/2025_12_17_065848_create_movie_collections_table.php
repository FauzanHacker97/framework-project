<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tmdb_movie_id');
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->text('overview')->nullable();
            $table->string('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->boolean('is_watched')->default(false);
            $table->text('personal_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'tmdb_movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_collections');
    }
};