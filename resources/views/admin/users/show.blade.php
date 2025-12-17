<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <p class="text-sm text-gray-500 mt-2">Member since {{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Users
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800">Total Movies</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ $user->movieCollections->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-green-800">Watched Movies</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $user->movieCollections->where('is_watched', true)->count() }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-purple-800">Reviews Written</h4>
                            <p class="text-2xl font-bold text-purple-600">{{ $user->reviews->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User's Collection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Movie Collection</h3>
                    @if($user->movieCollections->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($user->movieCollections as $collection)
                                <div class="text-center">
                                    <img 
                                        src="{{ $collection->poster_url }}" 
                                        alt="{{ $collection->title }}"
                                        class="w-full rounded shadow"
                                    >
                                    <p class="text-xs mt-2 truncate">{{ $collection->title }}</p>
                                    @if($collection->is_watched)
                                        <span class="text-xs text-green-600">✓ Watched</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No movies in collection.</p>
                    @endif
                </div>
            </div>

            <!-- User's Reviews -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Reviews</h3>
                    @if($user->reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->reviews as $review)
                                <div class="border-b pb-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('admin.reviews.show', $review) }}" class="font-semibold text-blue-600 hover:underline">
                                                {{ $review->movieCollection->title }}
                                            </a>
                                            <p class="text-sm">{{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)</p>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-2">{{ $review->review_text }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No reviews written.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>