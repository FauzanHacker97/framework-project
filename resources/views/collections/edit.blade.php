<x-app-layout>
    <x-slot name="title">Edit Collection: {{ $movieCollection->title }} - Movie Collection & Review Tracker</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Collection') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-6">{{ $movieCollection->title }}</h3>

                    <form action="{{ route('collections.update', $movieCollection) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_watched" value="1" {{ $movieCollection->is_watched ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700">I have watched this movie</span>
                            </label>
                        </div>

                        <div class="mb-6">
                            <label for="personal_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Personal Notes
                            </label>
                            <textarea 
                                name="personal_notes" 
                                id="personal_notes" 
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Add your personal notes about this movie..."
                            >{{ old('personal_notes', $movieCollection->personal_notes) }}</textarea>
                            @error('personal_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('collections.show', $movieCollection) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>