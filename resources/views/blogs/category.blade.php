@extends('layouts.app')

@section('title', $category->name . ' - Blog')
@section('description', $category->description ?? 'Browse blog posts in ' . $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Category Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            @if($category->icon)
                <span class="text-5xl mr-4">{{ $category->icon }}</span>
            @endif
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ $blogs->total() }} {{ Str::plural('post', $blogs->total()) }}</span>
            <a href="{{ route('blogs.index') }}" class="text-primary-600 hover:text-primary-700">← Back to all posts</a>
        </div>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mb-3"
                                  style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                                @if($blog->category->icon)
                                    <span class="mr-1">{{ $blog->category->icon }}</span>
                                @endif
                                {{ $blog->category->name }}
                            </span>
                        @endif

                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $blog->reading_time }} min read</span>
                            @if($blog->views > 0)
                                <span class="mx-2">•</span>
                                <span>{{ number_format($blog->views) }} views</span>
                            @endif
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-primary-600">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        @if($blog->excerpt)
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($blog->excerpt, 120) }}</p>
                        @endif

                        <div class="flex items-center justify-between">
                            <a href="{{ route('blog.show', $blog->slug) }}"
                               class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                                Read more
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                By {{ $blog->user->name }}
                            </span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($blogs->hasPages())
            <div class="mt-12">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No posts yet</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">There are no published posts in this category yet.</p>
            <div class="mt-6">
                <a href="{{ route('blogs.index') }}" class="btn btn-primary">
                    Browse all posts
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
