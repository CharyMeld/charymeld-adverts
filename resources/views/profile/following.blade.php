@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $user->name }} is Following</h1>

    @if($following->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($following as $followedUser)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <a href="{{ route('profile.show', $followedUser->name) }}" class="flex items-center gap-4">
                        @if($followedUser->avatar)
                            <img src="{{ asset('storage/' . $followedUser->avatar) }}" alt="{{ $followedUser->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ strtoupper(substr($followedUser->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $followedUser->name }}</h3>
                            @if($followedUser->profile)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($followedUser->profile->bio, 60) }}</p>
                            @endif
                        </div>
                    </a>
                    @auth
                        @if($followedUser->id !== auth()->id())
                            @if(auth()->user()->isFollowing($followedUser->id))
                                <form action="{{ route('unfollow', $followedUser->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm dark:bg-gray-700 dark:text-gray-200">
                                        Following
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('follow', $followedUser->id) }}" method="POST">
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
            {{ $following->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400">Not following anyone yet</p>
        </div>
    @endif
</div>
@endsection
