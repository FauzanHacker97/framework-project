<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $movie['title'] ?? 'Movie Details' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Poster -->
                        <div>
                            @if(isset($movie['poster_path']))
                                <img 
                                    src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                    alt="{{ $movie['title'] }}"
                                    class="w-full rounded-lg shadow-lg"
                                >
                            @else
                                <div class="w-full h-96 bg-gray-300 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500">No Image Available</span>
                                </div>
                            @endif

                            @auth
                                @if($inCollection)
                                    <div class="mt-4 bg-green-100 text-green-800 p-4 rounded text-center">
                                        ✓ In Your Collection
                                    </div>
                                @else
                                    <form action="{{ route('collections.store') }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="tmdb_movie_id" value="{{ $movie['id'] }}">
                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                            Add to Collection
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="mt-4 bg-gray-100 p-4 rounded text-center">
                                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                                        Login to add to collection
                                    </a>
                                </div>
                            @endauth
                        </div>

                        <!-- Details -->
                        <div class="md:col-span-2">
                            <h1 class="text-4xl font-bold mb-4">{{ $movie['title'] }}</h1>
                            
                            <div class="flex gap-4 mb-4 text-sm text-gray-600">
                                @if(isset($movie['release_date']))
                                    <span>📅 {{ date('Y', strtotime($movie['release_date'])) }}</span>
                                @endif
                                @if(isset($movie['runtime']))
                                    <span>⏱️ {{ $movie['runtime'] }} min</span>
                                @endif
                                @if(isset($movie['vote_average']))
                                    <span>⭐ {{ number_format($movie['vote_average'], 1) }}/10</span>
                                @endif
                            </div>

                            @if(isset($movie['genres']) && count($movie['genres']) > 0)
                                <div class="mb-4">
                                    @foreach($movie['genres'] as $genre)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mr-2">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if(isset($movie['tagline']) && $movie['tagline'])
                                <p class="text-lg italic text-gray-600 mb-4">"{{ $movie['tagline'] }}"</p>
                            @endif

                            <h3 class="text-xl font-semibold mb-2">Overview</h3>
                            <p class="text-gray-700 mb-6">
                                {{ $movie['overview'] ?? 'No overview available.' }}
                            </p>

                            @if(isset($movie['production_companies']) && count($movie['production_companies']) > 0)
                                <h3 class="text-xl font-semibold mb-2">Production</h3>
                                <p class="text-gray-700">
                                    @foreach($movie['production_companies'] as $company)
                                        {{ $company['name'] }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Backdrop Image -->
                    @if(isset($movie['backdrop_path']))
                        <div class="mt-8">
                            <img 
                                src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}" 
                                alt="{{ $movie['title'] }} backdrop"
                                class="w-full rounded-lg shadow-lg"
                            >
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>