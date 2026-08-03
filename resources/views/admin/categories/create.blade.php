@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg mt-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create New Category</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">← Back</a>
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

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
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
                   placeholder="e.g., Electronics, Vehicles">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Parent Category -->
        <div>
            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Parent Category
            </label>
            <select name="parent_id"
                    id="parent_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100">
                <option value="">None (This will be a parent category)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty to create a parent category, or select a parent to create a subcategory</p>
            @error('parent_id')
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
                   placeholder="📱 🚗 🏠 💼 👕"
                   maxlength="4">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter an emoji to represent this category</p>
            @error('icon')
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
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active (Category will be visible to users)</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">
                Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
