<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('admin.reviews.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                    All Reviews
                </a>
                <a href="{{ route('admin.reviews.index', ['filter' => 'pending']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200' }}">
                    Pending
                </a>
                <a href="{{ route('admin.reviews.index', ['filter' => 'approved']) }}" 
                   class="px-4 py-2 rounded {{ $filter === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                    Approved
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Reviews ({{ $reviews->total() }})</h3>

                    @if($reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-4 mb-2">
                                                <a href="{{ route('admin.reviews.show', $review) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                                                    {{ $review->movieCollection->title }}
                                                </a>
                                                @if($review->is_approved)
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Approved</span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pending</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                by <a href="{{ route('admin.users.show', $review->user) }}" class="text-blue-600 hover:underline">{{ $review->user->name }}</a>
                                            </p>
                                            <p class="text-sm">{{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)</p>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <p class="text-gray-700 mb-4">{{ Str::limit($review->review_text, 200) }}</p>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            View Details
                                        </a>
                                        
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-1 px-3 rounded">
                                                    Unapprove
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No reviews found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>