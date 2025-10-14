@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-primary-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Find Your Perfect Deal</h1>
        <p class="text-xl mb-8">Browse thousands of classified ads across Nigeria</p>

        <form action="{{ route('search') }}" method="GET" class="max-w-3xl">
            <div class="flex flex-col md:flex-row gap-2">
                <input type="text" name="q" placeholder="What are you looking for?"
                       class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300">
                <select name="category_id" class="px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary px-8">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Categories -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold mb-8">Browse by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}"
               class="card card-hover text-center p-6 group">
                <div class="text-5xl mb-3 transform group-hover:scale-110 transition-transform duration-300">
                    @if($category->icon)
                        {{ $category->icon }}
                    @else
                        🏷️
                    @endif
                </div>
                <h3 class="font-semibold text-lg mb-1 group-hover:text-primary-600 transition-colors">{{ $category->name }}</h3>
                <p class="text-sm text-gray-600">{{ $category->adverts_count ?? 0 }} ads</p>
            </a>
        @endforeach
    </div>

    <!-- Subcategories Section -->
    @foreach($categories as $parentCategory)
        @if($parentCategory->children && $parentCategory->children->count() > 0)
            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-6 flex items-center">
                    <span class="text-3xl mr-3">{{ $parentCategory->icon ?? '📂' }}</span>
                    {{ $parentCategory->name }} Subcategories
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($parentCategory->children as $child)
                        @if($child->is_active)
                            <a href="{{ route('category.show', $child->slug) }}"
                               class="bg-white rounded-xl p-4 hover:shadow-lg transition-all duration-300 border-2 border-transparent hover:border-primary-500 text-center group">
                                <div class="text-2xl mb-2">{{ $child->icon ?? '📌' }}</div>
                                <h4 class="font-medium text-sm group-hover:text-primary-600 transition-colors">{{ $child->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $child->adverts_count ?? 0 }} ads</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

<!-- Featured Adverts -->
@if($featuredAdverts->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-gray-100">
    <h2 class="text-3xl font-bold mb-8">⭐ Featured Ads</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredAdverts as $advert)
            <a href="{{ route('advert.show', $advert->slug) }}" class="card hover:shadow-xl transition-shadow overflow-hidden p-0">
                @if($advert->primaryImage)
                    <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                         alt="{{ $advert->title }}"
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-6xl">📷</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold mb-2 truncate">{{ $advert->title }}</h3>
                    <p class="text-primary-600 font-bold text-xl">₦{{ number_format($advert->price) }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <span>📍 {{ $advert->location ?? 'Nigeria' }}</span>
                    </p>
                    <span class="inline-block mt-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                        FEATURED
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- Latest Adverts -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold mb-8">Latest Ads</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($latestAdverts as $advert)
            <a href="{{ route('advert.show', $advert->slug) }}" class="card hover:shadow-xl transition-shadow overflow-hidden p-0">
                @if($advert->primaryImage)
                    <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                         alt="{{ $advert->title }}"
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-6xl">📷</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold mb-2 truncate">{{ $advert->title }}</h3>
                    <p class="text-primary-600 font-bold text-xl">₦{{ number_format($advert->price) }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <span>📍 {{ $advert->location ?? 'Nigeria' }}</span>
                    </p>
                    <span class="inline-block mt-2 text-xs text-gray-500">
                        {{ $advert->created_at->diffForHumans() }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Call to Action -->
<div class="bg-primary-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold mb-4">Start Selling Today!</h2>
        <p class="text-xl mb-8">Post your ad and reach thousands of buyers</p>
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
