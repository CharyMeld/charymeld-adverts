@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            @if(request('q'))
                Search Results for "{{ request('q') }}"
            @else
                All Adverts
            @endif
        </h1>
        <p class="text-gray-600">{{ $adverts->total() }} adverts found</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="card sticky top-4">
                <h3 class="font-bold text-lg mb-4">Filters</h3>

                <form action="{{ route('search') }}" method="GET" class="space-y-4">
                    <!-- Search Query -->
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}"
                               class="input" placeholder="Keywords...">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id" class="input">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}"
                                                {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ request('location') }}"
                               class="input" placeholder="e.g. Lagos">
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Range (₦)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" placeholder="Min"
                                   value="{{ request('min_price') }}"
                                   class="input">
                            <input type="number" name="max_price" placeholder="Max"
                                   value="{{ request('max_price') }}"
                                   class="input">
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select name="sort" id="sort" class="input">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 btn btn-primary">
                            Apply Filters
                        </button>
                        <a href="{{ route('search') }}" class="btn btn-secondary">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="lg:col-span-3">
            @if($adverts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($adverts as $advert)
                        <a href="{{ route('advert.show', $advert->slug) }}"
                           class="card hover:shadow-xl transition-shadow overflow-hidden p-0">
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
                                <div class="mb-2">
                                    @if($advert->plan === 'premium')
                                        <span class="badge bg-purple-100 text-purple-800 text-xs">💎 PREMIUM</span>
                                    @elseif($advert->plan === 'featured')
                                        <span class="badge bg-yellow-100 text-yellow-800 text-xs">⭐ FEATURED</span>
                                    @endif
                                </div>

                                <h3 class="font-semibold mb-2 truncate">{{ $advert->title }}</h3>
                                <p class="text-blue-600 font-bold text-xl">₦{{ number_format($advert->price) }}</p>

                                <div class="mt-2 flex items-center justify-between text-sm text-gray-600">
                                    <span class="truncate">📍 {{ $advert->location }}</span>
                                    <span>👁️ {{ $advert->views }}</span>
                                </div>

                                <p class="text-xs text-gray-500 mt-2">{{ $advert->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $adverts->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <span class="text-8xl mb-4 block">🔍</span>
                    <h3 class="text-2xl font-bold mb-2">No Results Found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
                    <a href="{{ route('search') }}" class="btn btn-primary">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
