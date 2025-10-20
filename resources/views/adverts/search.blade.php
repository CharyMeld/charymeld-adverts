@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Search Hero Section -->
    <div class="bg-gradient-to-r from-green-700 to-emerald-600 text-white py-8">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-4 text-center">
                @if(request('q'))
                    {{ __('messages.search.results_for') }} "{{ request('q') }}"
                @else
                    🔍 {{ __('messages.nav.search') }} & Discover
                @endif
            </h1>

            <!-- Quick Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="max-w-4xl mx-auto">
                <div class="flex gap-3 items-center">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Search for anything..."
                               class="w-full pl-12 pr-6 py-3 rounded-lg text-gray-900 focus:ring-4 focus:ring-green-300 border-0">
                    </div>
                    <button type="submit" class="px-8 py-3 bg-white hover:bg-gray-100 text-green-700 font-semibold rounded-lg transition shadow-lg">
                        Search
                    </button>
                </div>
            </form>

            <div class="mt-3 text-green-100 text-sm text-center">
                <span class="font-semibold">{{ $adverts->total() }}</span> {{ __('messages.filters.adverts_found') }}
            </div>
        </div>
    </div>

    <!-- Horizontal Filters Bar -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 shadow-md">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('search') }}" method="GET" class="space-y-3">
                <input type="hidden" name="q" value="{{ request('q') }}">

                <!-- Filters Row - Always in one row with horizontal scroll -->
                <div class="flex gap-3 overflow-x-auto pb-2 justify-center">
                    <!-- Category -->
                    <div class="flex-shrink-0 w-48">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">📂 Category</label>
                        <select name="category_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="flex-shrink-0 w-40">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">📍 Location</label>
                        <input type="text" name="location" value="{{ request('location') }}" placeholder="City or State"
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Min Price -->
                    <div class="flex-shrink-0 w-36">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">💰 Min Price</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="₦0"
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Max Price -->
                    <div class="flex-shrink-0 w-36">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">💵 Max Price</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="₦999,999"
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Condition -->
                    <div class="flex-shrink-0 w-32">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">✨ Condition</label>
                        <select name="condition" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All</option>
                            <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ request('condition') === 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="flex-shrink-0 w-44">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">🔄 Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 justify-center">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-700 to-emerald-600 hover:from-green-800 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg transition shadow">
                        Apply Filters
                    </button>
                    <a href="{{ route('search') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition">
                        Clear All
                    </a>

                    <!-- Active Filters Count -->
                    @php
                        $activeFilters = collect(request()->except(['q', 'page', 'sort']))->filter()->count();
                    @endphp
                    @if($activeFilters > 0)
                        <span class="px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm font-semibold rounded-lg">
                            {{ $activeFilters }} Active Filter{{ $activeFilters > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($adverts->count() > 0)
            <!-- Results Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
                @foreach($adverts as $advert)
                    <a href="{{ route('advert.show', $advert->slug) }}"
                       class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">

                        <!-- Image -->
                        <div class="relative overflow-hidden aspect-[4/3]">
                            @if($advert->primaryImage)
                                <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                     alt="{{ $advert->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                    <span class="text-5xl">📷</span>
                                </div>
                            @endif

                            <!-- Plan Badge -->
                            @if($advert->plan === 'premium')
                                <span class="absolute top-2 left-2 px-2 py-0.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-xs font-bold rounded-full shadow-lg">
                                    💎 PRO
                                </span>
                            @elseif($advert->plan === 'featured')
                                <span class="absolute top-2 left-2 px-2 py-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    ⭐
                                </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-3">
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-green-700 dark:group-hover:text-green-400 transition">
                                {{ $advert->title }}
                            </h3>

                            <div class="text-lg font-bold text-green-700 dark:text-green-500 mb-2">
                                ₦{{ number_format($advert->price) }}
                            </div>

                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1 truncate">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="truncate">{{ $advert->location }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>{{ number_format($advert->views) }}</span>
                                    </div>
                                    <span class="text-xs">{{ $advert->created_at->diffForHumans(null, true) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $adverts->appends(request()->except('page'))->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center max-w-2xl mx-auto">
                <div class="text-8xl mb-6">🔍</div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">{{ __('messages.search.no_results') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ __('messages.filters.no_results_message') }}
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('search') }}" class="px-8 py-3 bg-gradient-to-r from-green-700 to-emerald-600 hover:from-green-800 hover:to-emerald-700 text-white font-semibold rounded-lg transition shadow-lg">
                        {{ __('messages.filters.clear') }} {{ __('messages.filters.filters') }}
                    </a>
                    <a href="{{ route('home') }}" class="px-8 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg transition">
                        Go Home
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
