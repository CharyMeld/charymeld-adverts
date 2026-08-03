@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Cover Image -->
    <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 h-64 rounded-t-lg overflow-hidden">
        @if($group->cover_image)
            <img src="{{ asset('storage/' . $group->cover_image) }}" alt="{{ $group->name }}" class="w-full h-full object-cover">
        @endif
    </div>

    <!-- Group Header -->
    <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $group->name }}</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $group->description }}</p>
                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500 dark:text-gray-400">
                    <span>{{ $group->members->count() }} members</span>
                    <span>•</span>
                    <span>{{ ucfirst($group->privacy) }} group</span>
                    <span>•</span>
                    <span>Created {{ $group->created_at->diffForHumans() }}</span>
                </div>
            </div>

            @auth
                <div class="flex gap-2">
                    @if($isMember)
                        <a href="{{ route('group-chat.index', $group->slug) }}" class="btn bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                            💬 Chat
                        </a>
                        <a href="{{ route('groups.members', $group->slug) }}" class="btn bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-200">
                            👥 Members
                        </a>
                        @if($isAdmin)
                            <a href="{{ route('groups.edit', $group->slug) }}" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                ✏️ Edit
                            </a>
                        @endif
                        <form action="{{ route('groups.leave', $group->slug) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                Leave Group
                            </button>
                        </form>
                    @else
                        <form action="{{ route('groups.join', $group->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                + Join Group
                            </button>
                        </form>
                    @endif
                </div>
            @endauth
        </div>
    </div>

    <!-- Group Content (Members Preview) -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Recent Members</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($group->members->take(12) as $member)
                <a href="{{ route('profile.show', $member->name) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition">
                    @if($member->avatar)
                        <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-16 h-16 rounded-full mb-2 object-cover">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold mb-2">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-900 dark:text-white text-center">{{ $member->name }}</span>
                    @if($member->pivot->role !== 'member')
                        <span class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ ucfirst($member->pivot->role) }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        @if($group->members->count() > 12)
            <div class="text-center mt-6">
                <a href="{{ route('groups.members', $group->slug) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    View all {{ $group->members->count() }} members →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
