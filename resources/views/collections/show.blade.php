<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $movieCollection->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Poster -->
                        <div>
                            <img 
                                src="{{ $movieCollection->poster_url }}" 
                                alt="{{ $movieCollection->title }}"
                                class="w-full rounded-lg shadow-lg"
                            >

                            <!-- Action Buttons -->
                            <div class="mt-4 space-y-2">
                                <form action="{{ route('collections.toggle-watched', $movieCollection) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full {{ $movieCollection->is_watched ? 'bg-gray-500' : 'bg-green-500' }} hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                                        {{ $movieCollection->is_watched ? 'Mark as Unwatched' : 'Mark as Watched' }}
                                    </button>
                                </form>

                                <a href="{{ route('collections.edit', $movieCollection) }}" class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Edit Notes
                                </a>

                                @if($movieCollection->reviews->where('user_id', auth()->id())->isEmpty())
                                    <a href="{{ route('reviews.create', $movieCollection) }}" class="block w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">
                                        Write Review
                                    </a>
                                @endif

                                <form action="{{ route('collections.destroy', $movieCollection) }}" method="POST" onsubmit="return confirm('Remove this movie from your collection?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Remove from Collection
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="md:col-span-2">
                            <h1 class="text-4xl font-bold mb-4">{{ $movieCollection->title }}</h1>
                            
                            <div class="flex gap-4 mb-4 text-sm">
                                @if($movieCollection->release_date)
                                    <span class="text-gray-600">📅 {{ date('Y', strtotime($movieCollection->release_date)) }}</span>
                                @endif
                                <span class="text-gray-600">⭐ {{ number_format($movieCollection->vote_average, 1) }}/10</span>
                                @if($movieCollection->is_watched)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">✓ Watched</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Unwatched</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-semibold mb-2">Overview</h3>
                            <p class="text-gray-700 mb-6">
                                {{ $movieCollection->overview ?? 'No overview available.' }}
                            </p>

                            @if($movieCollection->personal_notes)
                                <h3 class="text-xl font-semibold mb-2">Personal Notes</h3>
                                <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                                    <p class="text-gray-700">{{ $movieCollection->personal_notes }}</p>
                                </div>
                            @endif

                            <!-- Reviews Section -->
                            <h3 class="text-xl font-semibold mb-4">Reviews</h3>
                            @if($movieCollection->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($movieCollection->reviews as $review)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p class="font-semibold">{{ $review->user->name }}</p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ str_repeat('⭐', $review->rating) }}
                                                        ({{ $review->rating }}/5)
                                                    </p>
                                                </div>
                                                @if($review->user_id === auth()->id())
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('reviews.edit', $review) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="text-gray-700">{{ $review->review_text }}</p>
                                            <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No reviews yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>