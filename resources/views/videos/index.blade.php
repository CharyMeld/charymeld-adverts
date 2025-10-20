@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Videos</h1>
        <a href="{{ route('videos.create') }}" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            📹 Upload Video
        </a>
    </div>

    @if($videos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($videos as $video)
                <a href="{{ route('videos.show', $video->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    @if($video->thumbnail_path)
                        <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                            <span class="text-6xl">🎥</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ Str::limit($video->title, 50) }}</h3>
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            @if($video->user->avatar)
                                <img src="{{ asset('storage/' . $video->user->avatar) }}" alt="{{ $video->user->name }}" class="w-6 h-6 rounded-full object-cover">
                            @else
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($video->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>{{ $video->user->name }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ number_format($video->views) }} views • {{ $video->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $videos->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No videos yet</p>
            <a href="{{ route('videos.create') }}" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition inline-block">
                📹 Upload Your First Video
            </a>
        </div>
    @endif
</div>
@endsection
