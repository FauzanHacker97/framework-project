<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $review->movieCollection->title }}</h3>
                            <p class="text-gray-600">Review by {{ $review->user->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Reviews
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Movie Poster -->
                        <div>
                            <img 
                                src="{{ $review->movieCollection->poster_url }}" 
                                alt="{{ $review->movieCollection->title }}"
                                class="w-full rounded shadow-lg"
                            >
                            
                            <!-- Status -->
                            <div class="mt-4">
                                @if($review->is_approved)
                                    <div class="bg-green-100 text-green-800 p-3 rounded text-center">
                                        ✓ Approved
                                    </div>
                                @else
                                    <div class="bg-yellow-100 text-yellow-800 p-3 rounded text-center">
                                        ⏳ Pending Approval
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Review Details -->
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Rating</h4>
                                <p class="text-2xl">{{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Review</h4>
                                <div class="bg-gray-50 p-4 rounded">
                                    <p class="text-gray-800">{{ $review->review_text }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Reviewer</h4>
                                <p>
                                    <a href="{{ route('admin.users.show', $review->user) }}" class="text-blue-600 hover:underline">
                                        {{ $review->user->name }}
                                    </a>
                                </p>
                                <p class="text-sm text-gray-600">{{ $review->user->email }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700">Submitted</h4>
                                <p class="text-sm text-gray-600">{{ $review->created_at->format('F d, Y \a\t h:i A') }}</p>
                                <p class="text-sm text-gray-600">{{ $review->created_at->diffForHumans() }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Approve Review
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                            Unapprove Review
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Delete Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>