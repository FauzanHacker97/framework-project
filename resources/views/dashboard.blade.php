<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Welcome back, {{ auth()->user()->name }}!</h3>
                    
                    @php
                        $totalMovies = auth()->user()->movieCollections()->count();
                        $watchedMovies = auth()->user()->movieCollections()->where('is_watched', true)->count();
                        $totalReviews = auth()->user()->reviews()->count();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-100 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-blue-800">Total Movies</h4>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalMovies }}</p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-green-800">Watched</h4>
                            <p class="text-3xl font-bold text-green-600">{{ $watchedMovies }}</p>
                        </div>
                        <div class="bg-purple-100 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-purple-800">Reviews Written</h4>
                            <p class="text-3xl font-bold text-purple-600">{{ $totalReviews }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('movies.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Browse Movies
                        </a>
                        <a href="{{ route('collections.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            My Collection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>