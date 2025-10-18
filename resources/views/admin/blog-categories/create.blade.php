@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg mt-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create Blog Category</h1>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.blog-categories.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Category Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Category Name <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                   required
                   placeholder="e.g., Technology, Marketing">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Slug (URL-friendly name)
            </label>
            <input type="text"
                   name="slug"
                   id="slug"
                   value="{{ old('slug') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                   placeholder="Leave empty to auto-generate">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty to automatically generate from category name</p>
            @error('slug')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Description
            </label>
            <textarea name="description"
                      id="description"
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                      placeholder="Brief description of this category...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Icon (Emoji) -->
        <div>
            <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Icon (Emoji)
            </label>
            <input type="text"
                   name="icon"
                   id="icon"
                   value="{{ old('icon') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                   placeholder="📝 💻 📱 🎨 📊"
                   maxlength="10">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter an emoji to represent this category</p>
            @error('icon')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Color -->
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Color
            </label>
            <input type="color"
                   name="color"
                   id="color"
                   value="{{ old('color', '#3B82F6') }}"
                   class="h-10 w-20 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select a color for this category</p>
            @error('color')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Order -->
        <div>
            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Display Order
            </label>
            <input type="number"
                   name="order"
                   id="order"
                   value="{{ old('order', 0) }}"
                   min="0"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lower numbers appear first</p>
            @error('order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Active Status -->
        <div>
            <label class="flex items-center">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       checked
                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active (Category will be visible)</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">
                Create Category
            </button>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
