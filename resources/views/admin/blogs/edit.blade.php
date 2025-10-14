@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Blog Post</h1>
            <p class="mt-1 text-gray-600">Update your blog post</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Posts
        </a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Post Details</h2>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                       class="input @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div class="mb-6">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Excerpt
                </label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="input @error('excerpt') border-red-500 @enderror"
                          placeholder="A brief summary of the post...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Will be displayed in post previews.</p>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea name="content" id="content" rows="15"
                          class="input @error('content') border-red-500 @enderror"
                          required>{{ old('content', $blog->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Use markdown or plain text for formatting.</p>
            </div>

            <!-- Current Featured Image -->
            @if($blog->featured_image)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                    <img src="{{ asset('storage/' . $blog->featured_image) }}"
                         alt="{{ $blog->title }}"
                         class="w-64 h-40 object-cover rounded-lg">
                </div>
            @endif

            <!-- Featured Image -->
            <div class="mb-6">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $blog->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                </label>
                <input type="file" name="featured_image" id="featured_image"
                       accept="image/*"
                       class="input @error('featured_image') border-red-500 @enderror">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Recommended size: 1200x630px</p>
            </div>

            <!-- Meta Description -->
            <div class="mb-6">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="2"
                          class="input @error('meta_description') border-red-500 @enderror"
                          placeholder="SEO meta description...">{{ old('meta_description', $blog->meta_description) }}</textarea>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. For SEO purposes (150-160 characters recommended).</p>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                        class="input @error('status') border-red-500 @enderror"
                        required>
                    <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published At (only if status is published) -->
            <div class="mb-6" id="published-at-field" style="display: none;">
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Publish Date
                </label>
                <input type="datetime-local" name="published_at" id="published_at"
                       value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                       class="input @error('published_at') border-red-500 @enderror">
                @error('published_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}"
                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Delete Post
                </button>
            </form>

            <div class="flex gap-4">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Post
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Show/hide published_at field based on status
    document.getElementById('status').addEventListener('change', function() {
        const publishedAtField = document.getElementById('published-at-field');
        if (this.value === 'published') {
            publishedAtField.style.display = 'block';
        } else {
            publishedAtField.style.display = 'none';
        }
    });

    // Trigger on page load
    document.getElementById('status').dispatchEvent(new Event('change'));
</script>
@endsection
