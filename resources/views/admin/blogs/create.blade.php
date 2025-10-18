@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Blog Post</h1>
            <p class="mt-1 text-gray-600">Write a new blog post</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Posts
        </a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Post Details</h2>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="input @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Category
                </label>
                <select name="category_id" id="category_id"
                        class="input @error('category_id') border-red-500 @enderror">
                    <option value="">-- Select Category --</option>
                    @foreach(\App\Models\BlogCategory::active()->ordered()->get() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            @if($category->icon){{ $category->icon }} @endif{{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Categorize your blog post.</p>
            </div>

            <!-- Excerpt -->
            <div class="mb-6">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Excerpt
                </label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="input @error('excerpt') border-red-500 @enderror"
                          placeholder="A brief summary of the post...">{{ old('excerpt') }}</textarea>
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
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Use markdown or plain text for formatting.</p>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Featured Image
                </label>
                <input type="file" name="featured_image" id="featured_image"
                       accept="image/*"
                       class="input @error('featured_image') border-red-500 @enderror">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Recommended size: 1200x630px</p>
            </div>

            <!-- Tags -->
            <div class="mb-6">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                    Tags
                </label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                       class="input @error('tags') border-red-500 @enderror"
                       placeholder="laravel, php, web development">
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Comma-separated tags (e.g., "laravel, php, tutorial").</p>
            </div>
        </div>

        <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-6">SEO Settings</h2>

            <!-- Meta Title -->
            <div class="mb-6">
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta Title
                </label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                       class="input @error('meta_title') border-red-500 @enderror"
                       placeholder="Leave empty to use post title"
                       maxlength="60">
                @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. For SEO purposes (50-60 characters recommended).</p>
            </div>

            <!-- Meta Description -->
            <div class="mb-6">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta Description
                </label>
                <textarea name="meta_description" id="meta_description" rows="2"
                          class="input @error('meta_description') border-red-500 @enderror"
                          placeholder="SEO meta description..."
                          maxlength="160">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. For SEO purposes (150-160 characters recommended).</p>
            </div>

            <!-- Meta Keywords -->
            <div class="mb-6">
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta Keywords
                </label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                       class="input @error('meta_keywords') border-red-500 @enderror"
                       placeholder="keyword1, keyword2, keyword3">
                @error('meta_keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Optional. Comma-separated keywords for SEO.</p>
            </div>
        </div>

        <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Publishing</h2>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                        class="input @error('status') border-red-500 @enderror"
                        required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
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
                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                       class="input @error('published_at') border-red-500 @enderror">
                @error('published_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Create Post
            </button>
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
