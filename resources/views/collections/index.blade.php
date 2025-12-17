<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Movie Collection') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-800">Total Movies</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-green-800">Watched</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['watched'] }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-yellow-800">Unwatched</h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['unwatched'] }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-purple-800">Reviews</h4>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['reviews'] }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('collections.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                    All
                </a>
                <a href="{{ route('collections.index', ['filter' => 'watched']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'watched' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                    Watched
                </a>
                <a href="{{ route('collections.index', ['filter' => 'unwatched']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'unwatched' ? 'bg-yellow-500 text-white' : 'bg-gray-200' }}">
                    Unwatched
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($collections->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($collections as $collection)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <a href="{{ route('collections.show', $collection) }}">
                                <img 
                                    src="{{ $collection->poster_url }}" 
                                    alt="{{ $collection->title }}"
                                    class="w-full h-80 object-cover"
                                >
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-sm mb-2 truncate">
                                    <a href="{{ route('collections.show', $collection) }}" class="hover:text-blue-600">
                                        {{ $collection->title }}
                                    </a>
                                </h3>
                                <div class="flex justify-between items-center text-xs text-gray-600 mb-2">
                                    <span>{{ $collection->release_date ? date('Y', strtotime($collection->release_date)) : 'N/A' }}</span>
                                    <span>⭐ {{ number_format($collection->vote_average, 1) }}</span>
                                </div>
                                <div class="flex gap-1 text-xs">
                                    @if($collection->is_watched)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">✓ Watched</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">Unwatched</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $collections->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded-lg shadow text-center">
                    <p class="text-gray-500 mb-4">Your collection is empty.</p>
                    <a href="{{ route('movies.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Browse Movies
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>