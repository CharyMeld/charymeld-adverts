@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Category Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="text-6xl">{{ $category->icon ?? '🏷️' }}</span>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600">{{ $adverts->total() }} adverts in this category</p>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children->count() > 0)
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('category.show', $category->slug) }}"
                   class="px-4 py-2 rounded-lg {{ !request('subcategory') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                    All
                </a>
                @foreach($category->children as $child)
                    <a href="{{ route('category.show', $category->slug) }}?subcategory={{ $child->slug }}"
                       class="px-4 py-2 rounded-lg {{ request('subcategory') === $child->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        {{ $child->name }} ({{ $child->adverts_count ?? 0 }})
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Adverts Grid -->
    @if($adverts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
            {{ $adverts->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <span class="text-8xl mb-4 block">📭</span>
            <h3 class="text-2xl font-bold mb-2">No Adverts Found</h3>
            <p class="text-gray-600 mb-6">There are no adverts in this category yet</p>
            @auth
                <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-primary">
                    Post First Ad
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection
