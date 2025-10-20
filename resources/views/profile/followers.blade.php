@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $user->name }}'s Followers</h1>

    @if($followers->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($followers as $follower)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <a href="{{ route('profile.show', $follower->name) }}" class="flex items-center gap-4">
                        @if($follower->avatar)
                            <img src="{{ asset('storage/' . $follower->avatar) }}" alt="{{ $follower->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ strtoupper(substr($follower->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $follower->name }}</h3>
                            @if($follower->profile)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($follower->profile->bio, 60) }}</p>
                            @endif
                        </div>
                    </a>
                    @auth
                        @if($follower->id !== auth()->id())
                            @if(auth()->user()->isFollowing($follower->id))
                                <form action="{{ route('unfollow', $follower->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm dark:bg-gray-700 dark:text-gray-200">
                                        Following
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('follow', $follower->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                        Follow
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $followers->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400">No followers yet</p>
        </div>
    @endif
</div>
@endsection
