@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ __('messages.blog.blog') }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('messages.blog.stay_updated') }}</p>
    </div>

    @if($blogs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogs as $blog)
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($blog->featured_image)
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                 alt="{{ $blog->title }}"
                                 class="w-full h-48 object-cover">
                        </a>
                    @endif

                    <div class="p-6">
                        @if($blog->category)
                            <a href="{{ route('blog.category', $blog->category->slug) }}"
                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mb-3"
                               style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                                @if($blog->category->icon)
                                    <span class="mr-1">{{ $blog->category->icon }}</span>
                                @endif
                                {{ $blog->category->name }}
                            </a>
                        @endif

                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $blog->reading_time }} {{ __('messages.blog.min_read') }}</span>
                            @if($blog->views > 0)
                                <span class="mx-2">•</span>
                                <span>{{ number_format($blog->views) }} {{ __('messages.blog.views') }}</span>
                            @endif
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        @if($blog->excerpt)
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($blog->excerpt, 120) }}</p>
                        @endif

                        <div class="flex items-center justify-between">
                            <a href="{{ route('blog.show', $blog->slug) }}"
                               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">
                                {{ __('messages.blog.read_more') }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('messages.blog.by') }} {{ $blog->user->name }}
                            </span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $blogs->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.blog.no_posts_yet') }}</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('messages.blog.check_back_soon') }}</p>
        </div>
    @endif
</div>
@endsection
