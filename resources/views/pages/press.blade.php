@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Press & Media Center
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            Latest news, press releases, and media resources for CharyMeld Adverts
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
        <div class="card text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">2024</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Founded</div>
        </div>
        <div class="card text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">50K+</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Active Users</div>
        </div>
        <div class="card text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">100K+</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Ads Posted</div>
        </div>
        <div class="card text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">5</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Languages</div>
        </div>
    </div>

    <!-- Press Releases -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Latest Press Releases</h2>
        <div class="space-y-6">
            <div class="card hover:shadow-xl transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-3xl">🚀</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">October 18, 2025</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            CharyMeld Adverts Launches Advanced Analytics & Multi-Language Support
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            CharyMeld Adverts announces major platform updates including Google Analytics 4 integration,
                            Meta Pixel tracking, and support for 5 languages (English, French, Spanish, Portuguese, Arabic).
                        </p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">
                            Read full press release →
                        </a>
                    </div>
                </div>
            </div>

            <div class="card hover:shadow-xl transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-3xl">💼</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">October 2025</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            CharyMeld Opens Partner Program for Affiliates and Strategic Partnerships
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Platform introduces comprehensive partner program with up to 20% commission rates,
                            real-time tracking, and dedicated support for affiliates and strategic partners.
                        </p>
                        <a href="{{ route('partners') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">
                            Learn more about partnership →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Resources -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Media Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Brand Assets -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Brand Assets</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Download our logos, brand guidelines, and media kit for your coverage.
                </p>
                <div class="space-y-3">
                    <a href="/downloads/charymeld-logos.zip" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <span class="font-medium dark:text-white">Logo Package (PNG, SVG)</span>
                        <span class="text-primary-600 dark:text-primary-400">⬇️ Download</span>
                    </a>
                    <a href="/downloads/brand-guidelines.pdf" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <span class="font-medium dark:text-white">Brand Guidelines (PDF)</span>
                        <span class="text-primary-600 dark:text-primary-400">⬇️ Download</span>
                    </a>
                    <a href="/downloads/media-kit.pdf" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <span class="font-medium dark:text-white">Complete Media Kit (PDF)</span>
                        <span class="text-primary-600 dark:text-primary-400">⬇️ Download</span>
                    </a>
                </div>
            </div>

            <!-- Screenshots -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Product Screenshots</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    High-resolution screenshots for your articles and reviews.
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-4xl">🏠</span>
                    </div>
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-4xl">📱</span>
                    </div>
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-4xl">📊</span>
                    </div>
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-4xl">💼</span>
                    </div>
                </div>
                <a href="/downloads/screenshots.zip" class="btn btn-secondary w-full mt-4">
                    Download All Screenshots
                </a>
            </div>
        </div>
    </div>

    <!-- About CharyMeld -->
    <div class="card mb-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">About CharyMeld Adverts</h2>
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                CharyMeld Adverts is a comprehensive advertising platform connecting advertisers, publishers, and users
                in a seamless ecosystem. Founded in 2024, the platform has grown to serve over 50,000 active users across
                Nigeria and beyond.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                The platform offers multiple advertising solutions including text ads, video campaigns, banner placements,
                and native advertising. With support for 5 languages and advanced analytics integration, CharyMeld provides
                a modern, user-friendly advertising experience.
            </p>
            <p class="text-gray-700 dark:text-gray-300">
                Key features include AI-powered search, real-time bidding, comprehensive analytics, multi-language support,
                progressive web app capabilities, and a robust partner ecosystem.
            </p>
        </div>
    </div>

    <!-- Media Contact -->
    <div class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-2xl p-12 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">Media Inquiries</h2>
        <p class="text-xl mb-6 opacity-90">
            For press inquiries, interviews, or additional information
        </p>
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
            <a href="mailto:press@charymeld.com" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                ✉️ press@charymeld.com
            </a>
            <a href="tel:+2348000000000" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                📞 +234 800 000 0000
            </a>
        </div>
    </div>
</div>
@endsection
