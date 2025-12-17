<x-app-layout>
    <x-slot name="title">Edit Review: {{ $review->movieCollection->title }} - Movie Collection & Review Tracker</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex gap-4 mb-6">
                        <img 
                            src="{{ $review->movieCollection->poster_url }}" 
                            alt="{{ $review->movieCollection->title }}"
                            class="w-24 h-36 object-cover rounded"
                        >
                        <div>
                            <h3 class="text-2xl font-bold">{{ $review->movieCollection->title }}</h3>
                            <p class="text-gray-600">{{ $review->movieCollection->release_date ? date('Y', strtotime($review->movieCollection->release_date)) : '' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">
                                Rating <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="rating" 
                                id="rating" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Select rating...</option>
                                <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                                <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 - Good)</option>
                                <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 - Average)</option>
                                <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2 - Poor)</option>
                                <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}>⭐ (1 - Terrible)</option>
                            </select>
                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="review_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Review <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="review_text" 
                                id="review_text" 
                                rows="8"
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Share your thoughts about this movie... (minimum 10 characters)"
                            >{{ old('review_text', $review->review_text) }}</textarea>
                            @error('review_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Review
                            </button>
                            <a href="{{ route('collections.show', $review->movieCollection) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>