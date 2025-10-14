@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Blog Management</h1>
            <p class="mt-1 text-gray-600">Manage blog posts and articles</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                + New Post
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                ← Back
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search blog posts..."
                       class="input">
            </div>
            <select name="status" class="input w-48">
                <option value="">All Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        </form>
    </div>

    <!-- Blog Posts Grid -->
    @if($blogs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($blogs as $blog)
                <div class="card card-hover overflow-hidden">
                    @if($blog->featured_image)
                        <img src="{{ asset('storage/' . $blog->featured_image) }}"
                             alt="{{ $blog->title }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 flex-1">
                                {{ Str::limit($blog->title, 50) }}
                            </h3>
                            @if($blog->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </div>

                        @if($blog->excerpt)
                            <p class="text-sm text-gray-600 mb-3">
                                {{ Str::limit($blog->excerpt, 100) }}
                            </p>
                        @endif

                        <div class="text-xs text-gray-500 mb-4 space-y-1">
                            <div>By {{ $blog->user->name }}</div>
                            <div>{{ $blog->created_at->format('M d, Y') }}</div>
                            @if($blog->views_count > 0)
                                <div>{{ $blog->views_count }} views</div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                               class="flex-1 btn btn-secondary btn-sm text-center">
                                View
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}"
                               class="flex-1 btn btn-primary btn-sm text-center">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            {{ $blogs->links() }}
        </div>
    @else
        <div class="card text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No blog posts yet</h3>
            <p class="mt-2 text-gray-500 mb-4">Get started by creating your first blog post</p>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                + Create Post
            </a>
        </div>
    @endif
</div>
@endsection
