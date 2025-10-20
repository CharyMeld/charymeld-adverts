@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $group->name }} - Members</h1>
        <a href="{{ route('groups.show', $group->slug) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
            ← Back to Group
        </a>
    </div>

    @if($members->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($members as $member)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <a href="{{ route('profile.show', $member->name) }}" class="flex items-center gap-4">
                        @if($member->avatar)
                            <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                                @if($member->pivot->role !== 'member')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $member->pivot->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($member->pivot->role) }}
                                    </span>
                                @endif
                            </div>
                            @if($member->profile)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($member->profile->bio, 60) }}</p>
                            @endif
                        </div>
                    </a>

                    @auth
                        @if($member->id !== auth()->id())
                            <a href="{{ route('chat.show', $member->id) }}" class="btn bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                💬 Message
                            </a>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400">No members yet</p>
        </div>
    @endif
</div>
@endsection
