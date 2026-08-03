@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Video</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Video Preview -->
        <div class="mb-6">
            <div class="bg-black rounded-lg overflow-hidden max-w-md">
                <video controls class="w-full" poster="{{ $video->thumbnail_path ? asset('storage/' . $video->thumbnail_path) : '' }}">
                    <source src="{{ route('videos.stream', $video->id) }}" type="{{ $video->mime_type }}">
                </video>
            </div>
        </div>

        <form action="{{ route('videos.update', $video->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $video->title) }}"
                       required
                       maxlength="200"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Enter video title">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description"
                          rows="4"
                          maxlength="2000"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Describe your video...">{{ old('description', $video->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Max 2000 characters</p>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Privacy -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy *</label>
                <select name="privacy" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="public" {{ old('privacy', $video->privacy) === 'public' ? 'selected' : '' }}>Public - Anyone can see</option>
                    <option value="unlisted" {{ old('privacy', $video->privacy) === 'unlisted' ? 'selected' : '' }}>Unlisted - Only with link</option>
                    <option value="private" {{ old('privacy', $video->privacy) === 'private' ? 'selected' : '' }}>Private - Only you</option>
                </select>
                @error('privacy')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Save Changes
                </button>
                <a href="{{ route('videos.show', $video->id) }}" class="btn bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-semibold dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Delete Video -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Once you delete a video, there is no going back. Please be certain.</p>
            <form action="{{ route('videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    Delete Video
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
