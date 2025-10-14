@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Blog</h1>
        <p class="mt-2 text-gray-600">Stay updated with our latest news and articles</p>
    </div>

    @if($blogs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogs as $blog)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($blog->featured_image)
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                 alt="{{ $blog->title }}"
                                 class="w-full h-48 object-cover">
                        </a>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>By {{ $blog->user->name }}</span>
                            @if($blog->views_count > 0)
                                <span class="mx-2">•</span>
                                <span>{{ $blog->views_count }} views</span>
                            @endif
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900 mb-3">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-primary-600">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        @if($blog->excerpt)
                            <p class="text-gray-600 mb-4">{{ $blog->excerpt }}</p>
                        @endif

                        <a href="{{ route('blog.show', $blog->slug) }}"
                           class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Read more
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $blogs->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No blog posts yet</h3>
            <p class="mt-2 text-gray-500">Check back soon for new content!</p>
        </div>
    @endif
</div>
@endsection
