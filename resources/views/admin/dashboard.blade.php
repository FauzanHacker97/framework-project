<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-blue-800">Total Users</h4>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-green-800">Total Collections</h4>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_collections'] }}</p>
                </div>
                <div class="bg-purple-100 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-purple-800">Total Reviews</h4>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_reviews'] }}</p>
                </div>
                <div class="bg-yellow-100 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-yellow-800">Pending Reviews</h4>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_reviews'] }}</p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2">Manage Users</h3>
                    <p class="text-gray-600">View and manage all registered users</p>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2">Manage Reviews</h3>
                    <p class="text-gray-600">Moderate user reviews</p>
                </a>
                <a href="{{ route('movies.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2">Browse Movies</h3>
                    <p class="text-gray-600">Search TMDB database</p>
                </a>
            </div>

            <!-- Recent Users -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Recent Users</h3>
                    @if($recentUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">
                                                    {{ $user->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No users yet.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Recent Reviews</h3>
                    @if($recentReviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentReviews as $review)
                                <div class="border-b pb-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('admin.reviews.show', $review) }}" class="font-semibold text-blue-600 hover:underline">
                                                {{ $review->movieCollection->title }}
                                            </a>
                                            <p class="text-sm text-gray-600">by {{ $review->user->name }}</p>
                                            <p class="text-sm">{{ str_repeat('⭐', $review->rating) }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-2">{{ Str::limit($review->review_text, 100) }}</p>
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
</x-app-layout>