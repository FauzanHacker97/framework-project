<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex gap-6">
                                    <img 
                                        src="{{ $review->movieCollection->poster_url }}" 
                                        alt="{{ $review->movieCollection->title }}"
                                        class="w-24 h-36 object-cover rounded"
                                    >
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold mb-2">
                                            <a href="{{ route('collections.show', $review->movieCollection) }}" class="hover:text-blue-600">
                                                {{ $review->movieCollection->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)
                                        </p>
                                        <p class="text-gray-700 mb-4">{{ $review->review_text }}</p>
                                        <div class="flex gap-4 items-center">
                                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            <a href="{{ route('reviews.edit', $review) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded-lg shadow text-center">
                    <p class="text-gray-500 mb-4">You haven't written any reviews yet.</p>
                    <a href="{{ route('collections.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to My Collection
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>