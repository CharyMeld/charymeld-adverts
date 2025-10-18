@extends('layouts.app')

@section('title', $blog->meta_title ?? $blog->title)
@section('description', $blog->meta_description ?? $blog->excerpt ?? strip_tags(Str::limit($blog->content, 160)))

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Blog Header -->
    <header class="mb-8">
        @if($blog->category)
            <a href="{{ route('blog.category', $blog->category->slug) }}"
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-4"
               style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                @if($blog->category->icon)
                    <span class="mr-1">{{ $blog->category->icon }}</span>
                @endif
                {{ $blog->category->name }}
            </a>
        @endif

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            {{ $blog->title }}
        </h1>

        <div class="flex flex-wrap items-center text-gray-600 dark:text-gray-400 gap-4 mb-6">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center mr-3">
                    <span class="text-white font-semibold">{{ substr($blog->user->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $blog->user->name }}</p>
                    <p class="text-sm">{{ $blog->published_at->format('M d, Y') }}</p>
                </div>
            </div>
            <span class="text-gray-400">•</span>
            <span>{{ $blog->reading_time }} {{ __('messages.blog.min_read') }}</span>
            @if($blog->views > 0)
                <span class="text-gray-400">•</span>
                <span>{{ number_format($blog->views) }} {{ __('messages.blog.views') }}</span>
            @endif
        </div>

        @if($blog->featured_image)
            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                 alt="{{ $blog->title }}"
                 class="w-full h-96 object-cover rounded-lg shadow-lg mb-8">
        @endif
    </header>

    <!-- Blog Content -->
    <div class="prose prose-lg max-w-none dark:prose-invert mb-12">
        {!! nl2br(e($blog->content)) !!}
    </div>

    <!-- Tags -->
    @if($blog->tags && count($blog->tags) > 0)
        <div class="mb-12 pb-8 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.blog.tags') }}:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($blog->tags as $tag)
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Comments Section -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            {{ __('messages.blog.comments') }} ({{ $blog->approvedComments->count() + $blog->approvedComments->sum(fn($c) => $c->replies->count()) }})
        </h2>

        <!-- Comment Form -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('messages.blog.leave_comment') }}</h3>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('blog.comment.store', $blog) }}" method="POST" class="space-y-4">
                @csrf

                @guest
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('messages.blog.name_required') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                   required>
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('messages.blog.email_required') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                   required>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endguest

                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('messages.blog.comment_required') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="comment" id="comment" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                              required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('messages.blog.post_comment') }}
                </button>
            </form>
        </div>

        <!-- Comments List -->
        <div class="space-y-6">
            @forelse($blog->approvedComments as $comment)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr($comment->author_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $comment->author_name }}</h4>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                        </div>
                    </div>

                    <!-- Replies -->
                    @if($comment->replies->count() > 0)
                        <div class="ml-14 mt-4 space-y-4">
                            @foreach($comment->replies as $reply)
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center">
                                                <span class="text-white text-sm font-semibold">{{ substr($reply->author_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <h5 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $reply->author_name }}</h5>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $reply->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">{{ __('messages.blog.no_comments_yet') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Related Posts -->
    @if($relatedBlogs->count() > 0)
        <div class="mt-16 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('messages.blog.related_posts') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $relatedBlog)
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        @if($relatedBlog->featured_image)
                            <a href="{{ route('blog.show', $relatedBlog->slug) }}">
                                <img src="{{ asset('storage/' . $relatedBlog->featured_image) }}"
                                     alt="{{ $relatedBlog->title }}"
                                     class="w-full h-32 object-cover">
                            </a>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="hover:text-primary-600">
                                    {{ $relatedBlog->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $relatedBlog->published_at->format('M d, Y') }} • {{ $relatedBlog->reading_time }} {{ __('messages.blog.min_read') }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</article>
@endsection
