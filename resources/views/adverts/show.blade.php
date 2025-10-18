@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image Gallery -->
            <div class="card p-0 overflow-hidden mb-6 relative">
                @if($advert->images->count() > 0)
                    <div id="mainImage" class="w-full relative group cursor-zoom-in overflow-hidden">
                        <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                             alt="{{ $advert->title }}"
                             class="w-full h-96 object-cover transition-transform duration-500 group-hover:scale-105"
                             id="previewImage">
                    </div>

                    @if($advert->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2 p-4 bg-gray-50">
                            @foreach($advert->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $advert->title }}"
                                     class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition"
                                     onclick="document.getElementById('previewImage').src = this.src;">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-8xl">📷</span>
                    </div>
                @endif
            </div>

            <!-- Lightbox Modal (fixed zoom) -->
            <div id="lightbox" class="hidden fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4">
                <img id="lightboxImage" src="" alt="Zoomed Image"
                     class="rounded-lg shadow-2xl transition-transform duration-300 transform scale-95 opacity-0 max-w-none max-h-none">
                <button onclick="closeLightbox()" class="absolute top-5 right-5 text-white text-4xl font-bold">&times;</button>
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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $advert->title }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6 pb-6 border-b dark:border-gray-700">
                    <span>📍 {{ $advert->location }}</span>
                    <span>👁️ {{ $advert->views }} {{ __('messages.ads.views') }}</span>
                    <span>📅 {{ $advert->created_at->diffForHumans() }}</span>
                    <span class="ml-auto">
                        <a href="{{ route('category.show', $advert->category->slug) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                            {{ $advert->category->name }}
                        </a>
                    </span>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">₦{{ number_format($advert->price) }}</span>
                </div>

                <div>
                    <h2 class="text-xl font-bold mb-3 dark:text-white">{{ __('messages.ads.description') }}</h2>
                    <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $advert->description }}</div>
                </div>

                <!-- Social Share Buttons -->
                <div class="mt-6 pt-6 border-t dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-3 dark:text-white">📤 Share this ad</h3>
                    <x-social-share
                        :url="route('advert.show', $advert->slug)"
                        :title="$advert->title"
                        :description="Str::limit($advert->description, 100)" />
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="card sticky top-4">
                <h3 class="text-xl font-bold mb-4 dark:text-white">{{ __('messages.ads.contact_seller') }}</h3>

                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-300">{{ substr($advert->contact_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold dark:text-white">{{ $advert->contact_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.ads.seller') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="tel:{{ $advert->contact_phone }}"
                       class="btn btn-primary w-full flex items-center justify-center"
                       onclick="trackAdContact({{ $advert->id }}, 'phone')">
                        📞 {{ __('messages.ads.call_seller') }}
                    </a>

                    @if($advert->contact_email)
                        <a href="mailto:{{ $advert->contact_email }}"
                           class="btn btn-secondary w-full flex items-center justify-center"
                           onclick="trackAdContact({{ $advert->id }}, 'email')">
                            ✉️ {{ __('messages.ads.email_seller') }}
                        </a>
                    @endif

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $advert->contact_phone) }}" target="_blank"
                       class="btn btn-success w-full flex items-center justify-center"
                       onclick="trackAdContact({{ $advert->id }}, 'whatsapp')">
                        💬 {{ __('messages.ads.whatsapp') }}
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t dark:border-gray-700">
                    <h4 class="font-semibold mb-3 dark:text-white">{{ __('messages.ads.safety_tips') }}</h4>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li>⚠️ {{ __('messages.ads.safety_tip_1') }}</li>
                        <li>🔍 {{ __('messages.ads.safety_tip_2') }}</li>
                        <li>💳 {{ __('messages.ads.safety_tip_3') }}</li>
                        <li>🚫 {{ __('messages.ads.safety_tip_4') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Adverts -->
    @if($similarAdverts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">💡 You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($similarAdverts as $similar)
                    <a href="{{ route('advert.show', $similar->slug) }}" class="card hover:shadow-xl transition-shadow overflow-hidden p-0">
                        @if($similar->primaryImage)
                            <img src="{{ asset('storage/' . $similar->primaryImage->image_path) }}"
                                 alt="{{ $similar->title }}"
                                 class="w-full h-40 object-cover hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-5xl">📷</span>
                            </div>
                        @endif
                        <div class="p-3">
                            <h3 class="font-semibold mb-1 truncate text-sm dark:text-white">{{ $similar->title }}</h3>
                            <p class="text-blue-600 dark:text-blue-400 font-bold">₦{{ number_format($similar->price) }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">📍 {{ $similar->location }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Related Blog Posts -->
    @if(isset($relatedBlogs) && count($relatedBlogs) > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">📰 Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}" class="card hover:shadow-xl transition-shadow overflow-hidden p-0 group">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                                 alt="{{ $blog->title }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">📝</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 line-clamp-2 dark:text-white">{{ $blog->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $blog->excerpt }}</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-3 font-medium">Read more →</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Zoom/Lightbox Script -->
<script>
    const preview = document.getElementById('previewImage');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');

    if (preview) {
        preview.addEventListener('click', () => {
            lightboxImage.src = preview.src;
            lightbox.classList.remove('hidden');

            // Animate zoom in
            requestAnimationFrame(() => {
                lightboxImage.classList.remove('scale-95', 'opacity-0');
                lightboxImage.classList.add('scale-100', 'opacity-100');
            });
        });
    }

    function closeLightbox() {
        lightboxImage.classList.add('scale-95', 'opacity-0');
        setTimeout(() => lightbox.classList.add('hidden'), 200);
    }

    // Close on background click
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    // Track ad view
    if (typeof trackAdView === 'function') {
        trackAdView({{ $advert->id }}, '{{ addslashes($advert->title) }}');
    }

    // Track contact clicks
    function trackAdContact(advertId, type) {
        fetch('{{ route('api.analytics.contact-click') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                advert_id: advertId,
                type: type
            })
        }).catch(err => console.log('Analytics tracking error:', err));
    }
</script>
@endsection

