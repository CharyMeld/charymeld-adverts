@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Upload Video</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Video File -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video File *</label>
                <input type="file"
                       name="video"
                       accept="video/mp4,video/webm,video/ogg"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Max 100MB (MP4, WebM, OGG)</p>
                @error('video')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
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
                          placeholder="Describe your video...">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Max 2000 characters</p>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Privacy -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy *</label>
                <select name="privacy" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="public" {{ old('privacy') === 'public' ? 'selected' : '' }}>Public - Anyone can see</option>
                    <option value="unlisted" {{ old('privacy') === 'unlisted' ? 'selected' : '' }}>Unlisted - Only with link</option>
                    <option value="private" {{ old('privacy') === 'private' ? 'selected' : '' }}>Private - Only you</option>
                </select>
                @error('privacy')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Upload Video
                </button>
                <a href="{{ route('videos.index') }}" class="btn bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-semibold dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h3 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">Upload Tips</h3>
            <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
                <li>• Best format: MP4 with H.264 codec</li>
                <li>• Recommended resolution: 1080p or 720p</li>
                <li>• Maximum file size: 100MB</li>
                <li>• Thumbnail will be generated automatically</li>
            </ul>
        </div>
    </div>
</div>
@endsection
