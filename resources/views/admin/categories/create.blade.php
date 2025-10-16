@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-8">
    <h1 class="text-2xl font-bold mb-6">Create New Category</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <!-- Category Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium mb-2">Category Name</label>
            <input type="text" name="name" id="name" class="border border-gray-300 rounded w-full p-2" required>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Parent Category -->
        <div class="mb-4">
            <label for="parent_id" class="block text-sm font-medium mb-2">Parent Category</label>
            <select name="parent_id" id="parent_id" class="border border-gray-300 rounded w-full p-2">
                <option value="">None</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Active Status -->
        <div class="mb-4">
            <label for="active" class="block text-sm font-medium mb-2">Active</label>
            <select name="active" id="active" class="border border-gray-300 rounded w-full p-2">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Save Category
        </button>
    </form>
</div>
@endsection

