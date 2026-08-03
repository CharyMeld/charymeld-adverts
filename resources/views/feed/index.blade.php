@extends('layouts.app')

@section('content')
<!-- Hero Section (only show on home page, not on /feed) -->
@if(request()->is('/'))
<!-- Hero Section -->
<div class="bg-gradient-to-r from-primary-600 to-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('messages.home.hero_title') }}</h1>
        <p class="text-xl mb-8">{{ __('messages.home.hero_subtitle') }}</p>

        <form action="{{ route('search') }}" method="GET" class="max-w-3xl">
            <div class="flex flex-col md:flex-row gap-2">
                <input type="text" name="q" placeholder="{{ __('messages.home.search_placeholder') }}"
                       class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300">
                <select name="category_id" class="px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300">
                    <option value="">{{ __('messages.categories.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn bg-white text-primary-600 hover:bg-gray-100 px-8 font-semibold">{{ __('messages.home.search_button') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Referral Program Banner -->
<div class="bg-gradient-to-r from-[#2E6F40] to-green-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-bold mb-3">
                    💰 Earn Money by Referring Friends!
                </h2>
                <p class="text-lg md:text-xl mb-4 text-white/90">
                    Share your referral link and earn up to 20% commission on every referral's first payment
                </p>
                <ul class="space-y-2 text-white/90 mb-6">
                    <li class="flex items-center justify-center md:justify-start">
                        <span class="text-2xl mr-3">✓</span>
                        <span class="text-lg">20% recurring commission on first payment</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <span class="text-2xl mr-3">✓</span>
                        <span class="text-lg">Real-time tracking dashboard</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <span class="text-2xl mr-3">✓</span>
                        <span class="text-lg">Monthly payouts via bank transfer</span>
                    </li>
                </ul>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    @auth
                        <a href="{{ route('referrals.dashboard') }}" class="btn bg-white text-[#2E6F40] hover:bg-gray-100 px-8 py-3 text-lg font-semibold rounded-lg shadow-lg">
                            🔗 Get Your Referral Link
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn bg-white text-[#2E6F40] hover:bg-gray-100 px-8 py-3 text-lg font-semibold rounded-lg shadow-lg">
                            🔗 Sign Up & Start Earning
                        </a>
                    @endauth
                    <a href="{{ route('partners') }}" class="btn bg-white/20 hover:bg-white/30 text-white px-8 py-3 text-lg font-semibold rounded-lg border-2 border-white/40">
                        Learn More →
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="text-center">
                    <div class="text-6xl mb-4">🤝</div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border-2 border-white/20">
                        <div class="text-5xl font-bold mb-2">20%</div>
                        <div class="text-lg">Commission</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ request()->is('/') ? 'Latest Updates' : 'Feed' }}</h1>
        <p class="text-gray-600 dark:text-gray-400">Discover the latest content from our community</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('feed.index', ['type' => 'all']) }}"
               class="flex-1 px-6 py-4 text-center font-semibold transition {{ $type === 'all' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                All
            </a>
            <a href="{{ route('feed.index', ['type' => 'videos']) }}"
               class="flex-1 px-6 py-4 text-center font-semibold transition {{ $type === 'videos' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                Videos
            </a>
            <a href="{{ route('feed.index', ['type' => 'blogs']) }}"
               class="flex-1 px-6 py-4 text-center font-semibold transition {{ $type === 'blogs' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                Blogs
            </a>
            <a href="{{ route('feed.index', ['type' => 'ads']) }}"
               class="flex-1 px-6 py-4 text-center font-semibold transition {{ $type === 'ads' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                Ads
            </a>
        </div>
    </div>

    <!-- Feed Items -->
    <div class="space-y-6">
        @forelse($items as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                <!-- Post Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $item['user_avatar'] }}" alt="{{ $item['user_name'] }}" class="w-10 h-10 rounded-full">
                            <div>
                                <a href="{{ route('profile.show', $item['user_name']) }}" class="font-semibold text-gray-900 dark:text-white hover:text-blue-600">
                                    {{ $item['user_name'] }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['time_ago'] }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item['type'] === 'video' ? 'bg-purple-100 text-purple-800' : ($item['type'] === 'blog' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($item['type']) }}
                        </span>
                    </div>
                </div>

                <!-- Post Content -->
                <a href="{{ $item['url'] }}" class="block">
                    @if($item['type'] === 'video')
                        <!-- Video Post -->
                        <div class="relative bg-black">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-96 object-cover">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-black bg-opacity-60 rounded-full p-4">
                                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif($item['image'])
                        <!-- Image for Blog/Ad -->
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-96 object-cover">
                    @endif

                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $item['title'] }}</h2>

                        @if(isset($item['category']))
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded mb-3">
                                {{ $item['category'] }}
                            </span>
                        @endif

                        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $item['content'] }}</p>

                        <!-- Meta Information -->
                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ number_format($item['views']) }} views
                            </span>

                            @if($item['type'] === 'ad' && isset($item['price']))
                                <span class="font-bold text-green-600">₦{{ number_format($item['price']) }}</span>
                            @endif

                            @if($item['type'] === 'ad' && isset($item['location']))
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $item['location'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>

                <!-- Reactions & Comments Bar (Facebook Style) -->
                @if(auth()->check())
                    @php
                        $reactionTypes = ['like' => '👍', 'love' => '❤️', 'laugh' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😠'];
                        $userReaction = null;
                        $totalReactions = 0;

                        if($item['type'] === 'video' && isset($item['reactions'])) {
                            $userReaction = $item['reactions']->where('user_id', auth()->id())->first();
                            $totalReactions = $item['reactions']->count();
                        }
                    @endphp

                    <!-- Reaction Summary -->
                    @if($totalReactions > 0)
                        <div class="px-6 py-2 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    @foreach(['like', 'love', 'laugh', 'wow', 'sad', 'angry'] as $type)
                                        @if(isset($item['reaction_counts'][$type]) && $item['reaction_counts'][$type] > 0)
                                            <span class="text-lg">{{ $reactionTypes[$type] }}</span>
                                        @endif
                                    @endforeach
                                    <span class="ml-1">{{ $totalReactions }}</span>
                                </div>
                                <button class="hover:underline" onclick="toggleComments('{{ $item['type'] }}', {{ $item['id'] }})">
                                    <span id="comment-count-{{ $item['type'] }}-{{ $item['id'] }}">0</span> Comments
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-around">
                            <!-- Like Button with Reaction Popup -->
                            <div class="relative" x-data="{ showReactions: false }">
                                <button
                                    @mouseenter="showReactions = true"
                                    @mouseleave="showReactions = false"
                                    @click="handleMainReaction('{{ $item['type'] }}', {{ $item['id'] }})"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ $userReaction ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}"
                                    id="main-reaction-btn-{{ $item['type'] }}-{{ $item['id'] }}"
                                >
                                    <span class="text-xl" id="main-reaction-emoji-{{ $item['type'] }}-{{ $item['id'] }}">
                                        {{ $userReaction ? $reactionTypes[$userReaction->type] : '👍' }}
                                    </span>
                                    <span class="font-semibold">{{ $userReaction ? ucfirst($userReaction->type) : 'Like' }}</span>
                                </button>

                                <!-- Reaction Popup (Facebook Style) -->
                                <div
                                    x-show="showReactions"
                                    @mouseenter="showReactions = true"
                                    @mouseleave="showReactions = false"
                                    class="absolute bottom-full left-0 mb-2 bg-white dark:bg-gray-800 rounded-full shadow-2xl px-2 py-2 flex gap-1 border border-gray-200 dark:border-gray-700"
                                    style="display: none;"
                                >
                                    @foreach($reactionTypes as $type => $emoji)
                                        <button
                                            onclick="handleReaction('{{ $type }}', '{{ $item['type'] }}', {{ $item['id'] }})"
                                            class="hover:scale-125 transition-transform duration-200 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full"
                                            title="{{ ucfirst($type) }}"
                                        >
                                            <span class="text-2xl">{{ $emoji }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Comment Button -->
                            <button
                                onclick="toggleComments('{{ $item['type'] }}', {{ $item['id'] }})"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-semibold">Comment</span>
                            </button>

                            <!-- Share Button -->
                            <button
                                onclick="sharePost('{{ $item['url'] }}')"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                <span class="font-semibold">Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div id="comments-{{ $item['type'] }}-{{ $item['id'] }}" class="hidden border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                        <!-- Comment Input -->
                        <div class="flex gap-3 mb-4">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <textarea
                                    id="comment-input-{{ $item['type'] }}-{{ $item['id'] }}"
                                    placeholder="Write a comment..."
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                    rows="2"
                                ></textarea>
                                <button
                                    onclick="postComment('{{ $item['type'] }}', {{ $item['id'] }})"
                                    class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold"
                                >
                                    Post Comment
                                </button>
                            </div>
                        </div>

                        <!-- Comments List -->
                        <div id="comments-list-{{ $item['type'] }}-{{ $item['id'] }}" class="space-y-4">
                            <!-- Comments will be loaded here dynamically -->
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No content found</h3>
                <p class="text-gray-600 dark:text-gray-400">There are no {{ $type === 'all' ? 'posts' : $type }} to display yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lastPage > 1)
        <div class="mt-8 flex justify-center gap-2">
            @if($currentPage > 1)
                <a href="{{ route('feed.index', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                    Previous
                </a>
            @endif

            @for($i = max(1, $currentPage - 2); $i <= min($lastPage, $currentPage + 2); $i++)
                <a href="{{ route('feed.index', array_merge(request()->query(), ['page' => $i])) }}" class="px-4 py-2 {{ $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} border border-gray-300 dark:border-gray-700 rounded-lg">
                    {{ $i }}
                </a>
            @endfor

            @if($currentPage < $lastPage)
                <a href="{{ route('feed.index', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                    Next
                </a>
            @endif
        </div>
    @endif
</div>

@if(auth()->check())
<script>
const reactionEmojis = {
    'like': '👍',
    'love': '❤️',
    'laugh': '😂',
    'wow': '😮',
    'sad': '😢',
    'angry': '😠'
};

// Handle reaction from popup
function handleReaction(type, itemType, itemId) {
    fetch('/feed/react', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            item_type: itemType,
            item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update main reaction button
            const mainBtn = document.getElementById(`main-reaction-btn-${itemType}-${itemId}`);
            const mainEmoji = document.getElementById(`main-reaction-emoji-${itemType}-${itemId}`);

            if (data.user_reaction) {
                mainEmoji.textContent = reactionEmojis[data.user_reaction];
                mainBtn.querySelector('span.font-semibold').textContent = data.user_reaction.charAt(0).toUpperCase() + data.user_reaction.slice(1);
                mainBtn.classList.add('text-blue-600', 'dark:text-blue-400');
                mainBtn.classList.remove('text-gray-600', 'dark:text-gray-400');
            } else {
                mainEmoji.textContent = '👍';
                mainBtn.querySelector('span.font-semibold').textContent = 'Like';
                mainBtn.classList.remove('text-blue-600', 'dark:text-blue-400');
                mainBtn.classList.add('text-gray-600', 'dark:text-gray-400');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Handle main reaction button click (toggle like)
function handleMainReaction(itemType, itemId) {
    handleReaction('like', itemType, itemId);
}

// Toggle comments section
function toggleComments(itemType, itemId) {
    const commentsSection = document.getElementById(`comments-${itemType}-${itemId}`);
    if (commentsSection.classList.contains('hidden')) {
        commentsSection.classList.remove('hidden');
        loadComments(itemType, itemId);
    } else {
        commentsSection.classList.add('hidden');
    }
}

// Load comments
function loadComments(itemType, itemId) {
    fetch(`/feed/comments/${itemType}/${itemId}`)
        .then(response => response.json())
        .then(data => {
            const commentsList = document.getElementById(`comments-list-${itemType}-${itemId}`);
            const commentCount = document.getElementById(`comment-count-${itemType}-${itemId}`);

            if (commentCount) {
                commentCount.textContent = data.comments.length;
            }

            if (data.comments.length === 0) {
                commentsList.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No comments yet. Be the first to comment!</p>';
                return;
            }

            commentsList.innerHTML = data.comments.map(comment => `
                <div class="flex gap-3">
                    ${comment.user_avatar ?
                        `<img src="${comment.user_avatar}" alt="${comment.user_name}" class="w-8 h-8 rounded-full">` :
                        `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                            ${comment.user_name.charAt(0).toUpperCase()}
                        </div>`
                    }
                    <div class="flex-1">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2">
                            <a href="/profile/${comment.user_name}" class="font-semibold text-gray-900 dark:text-white hover:underline">
                                ${comment.user_name}
                            </a>
                            <p class="text-gray-800 dark:text-gray-200 mt-1">${comment.comment}</p>
                        </div>
                        <div class="flex gap-4 mt-1 text-xs text-gray-500 dark:text-gray-400 px-2">
                            <span>${comment.time_ago}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

// Post comment
function postComment(itemType, itemId) {
    const input = document.getElementById(`comment-input-${itemType}-${itemId}`);
    const comment = input.value.trim();

    if (!comment) return;

    fetch('/feed/comment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            item_type: itemType,
            item_id: itemId,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadComments(itemType, itemId);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Share post
function sharePost(url) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post',
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(window.location.origin + url).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}
</script>
@endif
@endsection
