@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-primary-600 text-white">
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
                <button type="submit" class="btn btn-secondary px-8">{{ __('messages.home.search_button') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Referral Program Banner -->
<div class="bg-[#2E6F40] text-white">
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

<!-- Personalized Recommendations Section -->
@if(isset($recommendedAds) && $recommendedAds->count() > 0)
<div class="w-full bg-gradient-to-br from-blue-50 to-purple-50 px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">
                @auth
                    💡 Recommended For You
                @else
                    🔥 Trending Now
                @endauth
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                @auth
                    Based on your browsing history and interests
                @else
                    Most popular ads right now
                @endauth
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($recommendedAds as $advert)
                <a href="{{ route('advert.show', $advert->slug) }}"
                   class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all overflow-hidden">
                    @if($advert->primaryImage)
                        <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                             alt="{{ $advert->title }}"
                             class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-5xl">
                            📷
                        </div>
                    @endif
                    <div class="p-3">
                        <h4 class="font-semibold text-gray-800 mb-1 truncate text-sm">{{ $advert->title }}</h4>
                        <p class="text-primary-600 font-bold">₦{{ number_format($advert->price) }}</p>
                        <p class="text-xs text-gray-600 mt-1">📍 {{ $advert->location ?? 'Nigeria' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Browse by Category Section -->
<div class="w-full px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3">
            {{ __('messages.home.browse_categories') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">
            {{ __('messages.home.hero_subtitle') }}
        </p>
    </div>

    @foreach($categories as $category)
        @php
            // Get only 15 latest adverts per category
            $latestAdverts = $category->adverts->sortByDesc('created_at')->take(15);
        @endphp

        <div class="mb-12 border-b border-gray-200 pb-8">
            <h3 class="text-2xl font-semibold mb-6 flex items-center">
                <span class="text-3xl mr-2">
                    {{ $category->icon ?? match($category->name) {
                        'Vehicles' => '🚗',
                        'Real Estate' => '🏠',
                        'Electronics' => '💻',
                        'Fashion' => '👗',
                        'Services' => '🛠️',
                        default => '📦',
                    } }}
                </span>
                {{ $category->name }}
            </h3>

            @if($latestAdverts->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach($latestAdverts as $advert)
                        <a href="{{ route('advert.show', $advert->slug) }}"
                           class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all overflow-hidden">
                            @if($advert->primaryImage)
                                <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                     alt="{{ $advert->title }}"
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-5xl">
                                    📷
                                </div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-800 mb-1 truncate">{{ $advert->title }}</h4>
                                <p class="text-primary-600 font-bold">₦{{ number_format($advert->price) }}</p>
                                <p class="text-sm text-gray-600 mt-1">📍 {{ $advert->location ?? 'Nigeria' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No adverts yet in this category.</p>
            @endif
        </div>
    @endforeach
</div>

<!-- Call to Action -->
<div class="bg-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold mb-4 text-gray-900">Start Selling Today!</h2>
        <p class="text-xl mb-8 text-gray-700">Post your ad and reach thousands of buyers</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-secondary text-lg px-8 py-3 inline-block">
                Create Free Account
            </a>
        @else
            <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-secondary text-lg px-8 py-3 inline-block">
                Post Your Ad
            </a>
        @endguest
    </div>
</div>
@endsection

