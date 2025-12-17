<x-app-layout>
    <x-slot name="title">Browse Movies - Movie Collection & Review Tracker</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Movies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="mb-8">
                <form action="{{ route('movies.index') }}" method="GET" class="flex gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search for movies..." 
                        value="{{ $query ?? '' }}"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        Search
                    </button>
                    @if($query)
                        <a href="{{ route('movies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            @if(isset($query) && $query)
                <h3 class="text-xl font-semibold mb-4">Search Results for "{{ $query }}"</h3>
            @else
                <h3 class="text-xl font-semibold mb-4">Popular Movies</h3>
            @endif

            <!-- Movies Grid -->
            @if(isset($movies['results']) && count($movies['results']) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($movies['results'] as $movie)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <a href="{{ route('movies.show', $movie['id']) }}">
                                @if(isset($movie['poster_path']))
                                    <img 
                                        src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                        alt="{{ $movie['title'] }}"
                                        class="w-full h-80 object-cover"
                                    >
                                @else
                                    <div class="w-full h-80 bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-sm mb-2 truncate">
                                    <a href="{{ route('movies.show', $movie['id']) }}" class="hover:text-blue-600">
                                        {{ $movie['title'] }}
                                    </a>
                                </h3>
                                <div class="flex justify-between items-center text-xs text-gray-600">
                                    <span>{{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}</span>
                                    <span class="flex items-center">
                                        ⭐ {{ number_format($movie['vote_average'] ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white p-8 rounded-lg shadow text-center">
                    <p class="text-gray-500">No movies found. Try a different search term.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>