@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Cover Image -->
    <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 h-64 rounded-t-lg overflow-hidden">
        @if($user->profile && $user->profile->cover_image)
            <img src="{{ asset('storage/' . $user->profile->cover_image) }}"
                 alt="Cover"
                 class="w-full h-full object-cover">
        @endif
    </div>

    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow-lg p-6 -mt-20 relative">
        <div class="flex flex-col md:flex-row items-center md:items-end gap-4">
            <!-- Avatar -->
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
                     alt="{{ $user->name }}"
                     class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
            @else
                <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <!-- User Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>

                @if($user->profile)
                    <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $user->profile->bio }}</p>

                    <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500 dark:text-gray-400 justify-center md:justify-start">
                        @if($user->profile->location)
                            <span class="flex items-center">
                                📍 {{ $user->profile->location }}
                            </span>
                        @endif

                        @if($user->profile->website)
                            <a href="{{ $user->profile->website }}" target="_blank" class="flex items-center hover:text-blue-600">
                                🔗 Website
                            </a>
                        @endif

                        @if($user->profile->occupation)
                            <span class="flex items-center">
                                💼 {{ $user->profile->occupation }}
                            </span>
                        @endif
                    </div>

                    <!-- Social Links -->
                    @if($user->profile->social_links)
                        <div class="flex gap-3 mt-3 justify-center md:justify-start">
                            @foreach($user->profile->social_links as $platform => $url)
                                <a href="{{ $url }}" target="_blank" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                                    @if($platform === 'facebook') 📘
                                    @elseif($platform === 'twitter') 🐦
                                    @elseif($platform === 'instagram') 📷
                                    @elseif($platform === 'linkedin') 💼
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        ✏️ Edit Profile
                    </a>
                @else
                    @if($isFollowing)
                        <form action="{{ route('unfollow', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-200">
                                Following
                            </button>
                        </form>
                    @else
                        <form action="{{ route('follow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                + Follow
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('chat.show', $user->id) }}" class="btn bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        💬 Message
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="flex gap-8 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 justify-center md:justify-start">
            <a href="{{ route('followers', $user->id) }}" class="text-center hover:text-blue-600">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->followers->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Followers</div>
            </a>

            <a href="{{ route('following', $user->id) }}" class="text-center hover:text-blue-600">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->following->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Following</div>
            </a>

            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->videos->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Videos</div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->groups->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Groups</div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-8" x-data="{ tab: 'videos' }">
        <div class="flex gap-4 border-b border-gray-200 dark:border-gray-700">
            <button @click="tab = 'videos'" :class="tab === 'videos' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2 font-medium">
                Videos
            </button>
            <button @click="tab = 'groups'" :class="tab === 'groups' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2 font-medium">
                Groups
            </button>
            <button @click="tab = 'about'" :class="tab === 'about' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2 font-medium">
                About
            </button>
        </div>

        <!-- Videos Tab -->
        <div x-show="tab === 'videos'" class="mt-6">
            @if($user->videos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->videos as $video)
                        <a href="{{ route('videos.show', $video->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            @if($video->thumbnail_path)
                                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-4xl">🎥</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $video->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $video->views }} views • {{ $video->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-12">No videos yet</p>
            @endif
        </div>

        <!-- Groups Tab -->
        <div x-show="tab === 'groups'" class="mt-6">
            @if($user->groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->groups as $group)
                        <a href="{{ route('groups.show', $group->slug) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            @if($group->cover_image)
                                <img src="{{ asset('storage/' . $group->cover_image) }}" alt="{{ $group->name }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $group->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $group->members->count() }} members</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-12">Not in any groups yet</p>
            @endif
        </div>

        <!-- About Tab -->
        <div x-show="tab === 'about'" class="mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                @if($user->profile)
                    <div class="space-y-4">
                        @if($user->profile->company)
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Company</h3>
                                <p class="text-gray-600 dark:text-gray-300">{{ $user->profile->company }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Member Since</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ $user->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No additional information</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
