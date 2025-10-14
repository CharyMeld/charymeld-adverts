@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categories Management</h1>
            <p class="mt-1 text-gray-600">Manage advertisement categories</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                + Add Category
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                ← Back
            </a>
        </div>
    </div>

    <!-- Categories Count -->
    <div class="card mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
            </div>
            <div class="text-sm text-gray-600">
                Showing {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($categories as $category)
            <div class="card">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $category->slug }}</p>
                        @if($category->parent)
                            <p class="text-xs text-gray-500 mt-1">Parent: {{ $category->parent->name }}</p>
                        @endif
                    </div>
                    @if($category->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </div>

                @if($category->description)
                    <p class="text-gray-700 mb-4">{{ Str::limit($category->description, 100) }}</p>
                @endif

                <div class="text-sm text-gray-600 mb-4">
                    <span class="font-semibold">{{ $category->adverts_count ?? 0 }}</span> adverts
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="flex-1 btn btn-secondary btn-sm text-center">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn btn-sm {{ $category->is_active ? 'btn-danger' : 'btn-success' }}">
                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full card text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No categories yet</h3>
                <p class="mt-2 text-gray-500 mb-4">Get started by creating your first category</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    + Create Category
                </a>
            </div>
        @endforelse
    </div>

    @if($categories->hasPages())
        <div class="card">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
