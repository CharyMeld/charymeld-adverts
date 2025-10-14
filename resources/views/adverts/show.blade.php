@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image Gallery -->
            <div class="card p-0 overflow-hidden mb-6">
                @if($advert->images->count() > 0)
                    <div id="mainImage" class="w-full">
                        <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                             alt="{{ $advert->title }}"
                             class="w-full h-96 object-cover">
                    </div>

                    @if($advert->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2 p-4 bg-gray-50">
                            @foreach($advert->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $advert->title }}"
                                     class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition {{ $loop->first ? 'ring-2 ring-blue-500' : '' }}"
                                     onclick="document.getElementById('mainImage').querySelector('img').src = this.src; document.querySelectorAll('#mainImage').forEach(el => el.classList.remove('ring-2', 'ring-blue-500')); this.classList.add('ring-2', 'ring-blue-500');">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-8xl">📷</span>
                    </div>
                @endif
            </div>

            <!-- Advert Details -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        @if($advert->plan === 'premium')
                            <span class="badge bg-purple-100 text-purple-800 mb-2 inline-block">💎 PREMIUM</span>
                        @elseif($advert->plan === 'featured')
                            <span class="badge bg-yellow-100 text-yellow-800 mb-2 inline-block">⭐ FEATURED</span>
                        @endif
                        <h1 class="text-3xl font-bold text-gray-900">{{ $advert->title }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b">
                    <span>📍 {{ $advert->location }}</span>
                    <span>👁️ {{ $advert->views }} views</span>
                    <span>📅 {{ $advert->created_at->diffForHumans() }}</span>
                    <span class="ml-auto">
                        <a href="{{ route('category.show', $advert->category->slug) }}" class="text-blue-600 hover:text-blue-700">
                            {{ $advert->category->name }}
                        </a>
                    </span>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-blue-600">₦{{ number_format($advert->price) }}</span>
                </div>

                <div>
                    <h2 class="text-xl font-bold mb-3">Description</h2>
                    <div class="text-gray-700 whitespace-pre-line">{{ $advert->description }}</div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Seller Contact -->
            <div class="card sticky top-4">
                <h3 class="text-xl font-bold mb-4">Contact Seller</h3>

                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-xl font-bold text-blue-600">{{ substr($advert->contact_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $advert->contact_name }}</p>
                            <p class="text-sm text-gray-600">Seller</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="tel:{{ $advert->contact_phone }}" class="btn btn-primary w-full flex items-center justify-center">
                        📞 Call Seller
                    </a>

                    @if($advert->contact_email)
                        <a href="mailto:{{ $advert->contact_email }}" class="btn btn-secondary w-full flex items-center justify-center">
                            ✉️ Email Seller
                        </a>
                    @endif

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $advert->contact_phone) }}" target="_blank"
                       class="btn btn-success w-full flex items-center justify-center">
                        💬 WhatsApp
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold mb-3">Safety Tips</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="mr-2">⚠️</span>
                            <span>Meet seller at a safe public location</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">🔍</span>
                            <span>Inspect the item before payment</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">💳</span>
                            <span>Never pay in advance</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">🚫</span>
                            <span>Report suspicious activity</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Share -->
            <div class="card mt-6">
                <h3 class="font-bold mb-3">Share this Ad</h3>
                <div class="flex gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('advert.show', $advert->slug)) }}"
                       target="_blank"
                       class="flex-1 btn btn-secondary text-center">
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('advert.show', $advert->slug)) }}&text={{ urlencode($advert->title) }}"
                       target="_blank"
                       class="flex-1 btn btn-secondary text-center">
                        Twitter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Adverts -->
    @if($similarAdverts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Similar Adverts</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarAdverts as $similar)
                    <a href="{{ route('advert.show', $similar->slug) }}" class="card hover:shadow-xl transition-shadow overflow-hidden p-0">
                        @if($similar->primaryImage)
                            <img src="{{ asset('storage/' . $similar->primaryImage->image_path) }}"
                                 alt="{{ $similar->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-6xl">📷</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 truncate">{{ $similar->title }}</h3>
                            <p class="text-blue-600 font-bold text-xl">₦{{ number_format($similar->price) }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                <span>📍 {{ $similar->location }}</span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
