@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-700 to-emerald-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">About CharyMeld Adverts</h1>
            <p class="text-xl md:text-2xl text-green-100 max-w-3xl mx-auto">
                Nigeria's #1 Social Marketplace - Where Commerce Meets Community
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Who We Are -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 mb-12">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Who We Are</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-green-700 to-emerald-600 mx-auto"></div>
            </div>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                CharyMeld Adverts is Nigeria's premier social marketplace platform that revolutionizes how people buy, sell, connect, and engage online.
                We're more than just a classifieds platform – we're a thriving community where commerce meets social networking.
            </p>
            <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                Launched with the vision of empowering Nigerians to trade, connect, and grow, CharyMeld Adverts combines the best of
                online marketplaces with powerful social features, creating an ecosystem where users can discover products, build networks,
                share content, and monetize their influence.
            </p>
        </div>

        <!-- Our Mission & Vision -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-8">
                <div class="text-4xl mb-4">🎯</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Mission</h3>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    To empower every Nigerian with a safe, innovative platform to buy, sell, connect, and earn online –
                    making commerce accessible, social engaging, and opportunities unlimited.
                </p>
            </div>
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-8">
                <div class="text-4xl mb-4">🚀</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Our Vision</h3>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    To become Africa's leading social commerce platform, where millions of users seamlessly blend shopping,
                    socializing, and earning in one vibrant digital ecosystem.
                </p>
            </div>
        </div>

        <!-- What We Offer -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 mb-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">What CharyMeld Adverts Offers</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">A complete ecosystem for buying, selling, connecting, and earning</p>
            </div>

            <!-- Marketplace Features -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="text-3xl mr-3">🛍️</span> Marketplace Features
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📝 Post Adverts</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">List items for sale with photos, descriptions, and pricing. Choose from Regular, Featured, or Premium plans for maximum visibility.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🔍 Advanced Search</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Find exactly what you need with powerful search filters by category, location, price range, and condition.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📊 Campaign Management</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Create text and video ad campaigns to promote your products to a wider audience.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📂 Categories</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Browse through multiple categories: Electronics, Fashion, Vehicles, Real Estate, Jobs, Services, and more.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">💳 Secure Payments</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Multiple payment options including card payments, bank transfers, and cash on delivery.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📍 Location-Based</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Find items near you or target specific locations for your adverts.</p>
                    </div>
                </div>
            </div>

            <!-- Social Features -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="text-3xl mr-3">👥</span> Social & Community Features
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📰 News Feed</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Share updates, photos, thoughts, and engage with your network through likes, comments, and shares.</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🎥 Video Sharing</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Upload and share product demos, tutorials, reviews, and creative content with the community.</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">👨‍👩‍👧‍👦 Groups</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Join or create interest-based groups for Electronics, Fashion, Cars, Real Estate, and more. Chat and collaborate with members.</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">💬 Direct Messaging</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Chat privately with buyers, sellers, and other users. Send messages, photos, and files securely.</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">👤 Follow System</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Follow users, build your network, and see updates from people and businesses you care about.</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🔔 Notifications</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Stay updated with real-time notifications for messages, likes, comments, and important activities.</p>
                    </div>
                </div>
            </div>

            <!-- Monetization Features -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="text-3xl mr-3">💰</span> Earn Money on CharyMeld
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6 border-2 border-green-200 dark:border-green-700">
                        <div class="flex items-center mb-3">
                            <span class="text-3xl mr-3">🎁</span>
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-white">Referral Program</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                            Invite friends and family using your unique referral link and earn commission when they sign up and make purchases.
                        </p>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>✓ Get your unique referral link</li>
                            <li>✓ Share on WhatsApp, Facebook, Twitter</li>
                            <li>✓ Earn money for each successful referral</li>
                            <li>✓ Track earnings in your dashboard</li>
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg p-6 border-2 border-emerald-200 dark:border-emerald-700">
                        <div class="flex items-center mb-3">
                            <span class="text-3xl mr-3">📺</span>
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-white">Publisher Program</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                            Monetize your website, blog, or social media by displaying CharyMeld ads and earn per click.
                        </p>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>✓ Register as a publisher</li>
                            <li>✓ Get ad codes for your site</li>
                            <li>✓ Earn money per ad click</li>
                            <li>✓ View analytics and earnings</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Security & Safety -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="text-3xl mr-3">🔐</span> Security & Safety Features
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🔒 Two-Factor Auth</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Protect your account with TOTP authenticator apps for extra security.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📱 Login Alerts</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Get notified of new logins and suspicious account activity.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🔑 Account Recovery</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Request account recovery if you lose access to your account.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🚨 Report System</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Report suspicious activity, scams, or security concerns instantly.</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Features -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="text-3xl mr-3">📊</span> Powerful Dashboards
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Advertiser Dashboard</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>✓ Manage all your adverts in one place</li>
                            <li>✓ Track impressions, views, and clicks</li>
                            <li>✓ Run text and video ad campaigns</li>
                            <li>✓ View payment history and transactions</li>
                            <li>✓ Renew, edit, pause, or delete adverts</li>
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Admin Dashboard</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>✓ User management and verification</li>
                            <li>✓ Content moderation (adverts, posts, videos)</li>
                            <li>✓ Platform analytics and reports</li>
                            <li>✓ Category and pricing management</li>
                            <li>✓ Security reports and account recovery</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-gradient-to-r from-green-700 to-emerald-600 rounded-2xl shadow-xl p-8 md:p-12 mb-12 text-white">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Why Choose CharyMeld Adverts?</h2>
                <p class="text-lg text-green-100">The platform that combines everything you need in one place</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl mb-4">🚀</div>
                    <h4 class="font-semibold text-lg mb-2">All-in-One Platform</h4>
                    <p class="text-sm text-green-100">Marketplace, social network, and earning opportunities in one ecosystem</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h4 class="font-semibold text-lg mb-2">Safe & Secure</h4>
                    <p class="text-sm text-green-100">Advanced security features including 2FA, account recovery, and fraud protection</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4">👥</div>
                    <h4 class="font-semibold text-lg mb-2">Growing Community</h4>
                    <p class="text-sm text-green-100">Join thousands of Nigerians buying, selling, and connecting every day</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4">💰</div>
                    <h4 class="font-semibold text-lg mb-2">Multiple Income Streams</h4>
                    <p class="text-sm text-green-100">Sell products, earn referral commissions, become a publisher</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 mb-12">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-green-700 mb-2">1000+</div>
                    <p class="text-gray-600 dark:text-gray-400">Active Users</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-emerald-600 mb-2">500+</div>
                    <p class="text-gray-600 dark:text-gray-400">Daily Listings</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">20+</div>
                    <p class="text-gray-600 dark:text-gray-400">Categories</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-teal-600 mb-2">24/7</div>
                    <p class="text-gray-600 dark:text-gray-400">Support Available</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-8 md:p-12 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Ready to Get Started?</h2>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                Join CharyMeld Adverts today and experience Nigeria's most comprehensive social marketplace.
                Buy, sell, connect, and earn - all in one place!
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-green-700 to-emerald-600 hover:from-green-800 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg transition transform hover:scale-105">
                        Create Free Account
                    </a>
                    <a href="{{ route('onboarding.tour') }}" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-lg shadow-lg border-2 border-gray-300 dark:border-gray-600 hover:border-green-700 dark:hover:border-green-500 transition">
                        Take a Tour
                    </a>
                @else
                    <a href="{{ route('advertiser.adverts.create') }}" class="px-8 py-4 bg-gradient-to-r from-green-700 to-emerald-600 hover:from-green-800 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-lg transition transform hover:scale-105">
                        Post Your First Ad
                    </a>
                    <a href="{{ route('search') }}" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-lg shadow-lg border-2 border-gray-300 dark:border-gray-600 hover:border-green-700 dark:hover:border-green-500 transition">
                        Browse Marketplace
                    </a>
                @endguest
            </div>
        </div>

    </div>
</div>
@endsection
