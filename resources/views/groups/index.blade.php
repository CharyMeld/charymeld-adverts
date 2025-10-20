@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Groups</h1>
        <a href="{{ route('groups.create') }}" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            + Create Group
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($groups as $group)
            <a href="{{ route('groups.show', $group->slug) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                @if($group->cover_image)
                    <img src="{{ asset('storage/' . $group->cover_image) }}" alt="{{ $group->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $group->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ Str::limit($group->description, 100) }}</p>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $group->members_count }} members</span>
                        <span class="mx-2">•</span>
                        <span>{{ ucfirst($group->privacy) }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $groups->links() }}
    </div>
</div>
@endsection
