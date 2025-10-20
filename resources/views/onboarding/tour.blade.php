@extends('layouts.tour')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4" x-data="onboardingTour()">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Step <span x-text="currentStep + 1"></span> of <span x-text="totalSteps"></span>
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-3 rounded-full transition-all duration-500"
                     :style="`width: ${((currentStep + 1) / totalSteps) * 100}%`"></div>
            </div>
        </div>

        <!-- Tour Steps -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 md:p-12 min-h-[500px] relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-pink-400/10 to-blue-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <!-- Step 1: Welcome -->
                <div x-show="currentStep === 0" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">👋</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Welcome to {{ config('app.name') }}!
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Nigeria's #1 marketplace for buying and selling. Let me show you around!
                    </p>
                    <div class="grid md:grid-cols-3 gap-4 mt-8">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">🛍️</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Buy & Sell</p>
                        </div>
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">🔒</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Safe & Secure</p>
                        </div>
                        <div class="p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">⚡</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Fast & Easy</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Search & Browse -->
                <div x-show="currentStep === 1" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🔍</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Search & Browse
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Find what you're looking for with our powerful search and category browsing
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Search Bar</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Type what you're looking for in the top search bar</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📂</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Categories</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Browse by category: Electronics, Fashion, Vehicles, Real Estate & more</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🎯</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Filters</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Refine results by price, location, and condition</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Post Adverts -->
                <div x-show="currentStep === 2" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">✍️</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Post Your Adverts
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Selling is easy! Follow these simple steps
                    </p>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-6 text-left space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                            <p class="text-gray-800 dark:text-gray-200">Click "Post Ad" button</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                            <p class="text-gray-800 dark:text-gray-200">Fill in item details & upload photos</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                            <p class="text-gray-800 dark:text-gray-200">Choose a pricing plan</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                            <p class="text-gray-800 dark:text-gray-200">Submit and go live!</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Pricing Plans -->
                <div x-show="currentStep === 3" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">💰</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Pricing Plans
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Choose the plan that works best for you
                    </p>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <h3 class="font-bold text-lg mb-2">Regular</h3>
                            <p class="text-3xl font-bold text-blue-600 mb-2">₦1,000</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">30 days active</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/30 dark:to-purple-900/30 rounded-lg p-6 border-2 border-blue-500">
                            <div class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded mb-2">POPULAR</div>
                            <h3 class="font-bold text-lg mb-2">Featured</h3>
                            <p class="text-3xl font-bold text-blue-600 mb-2">₦3,000</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">30 days, top results</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <h3 class="font-bold text-lg mb-2">Premium</h3>
                            <p class="text-3xl font-bold text-blue-600 mb-2">₦5,000</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">60 days, homepage</p>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Social Feed & Community -->
                <div x-show="currentStep === 4" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">👥</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Social Feed & Community
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Connect with others, share updates, and engage with the community
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📰</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">News Feed</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Share posts, photos, and updates with your network</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">❤️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Like, Comment & Share</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Engage with posts from other users</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">👤</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Follow Users</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Build your network and see updates from people you follow</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Videos & Content -->
                <div x-show="currentStep === 5" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🎥</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Videos & Content Creation
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Share and discover video content from the community
                    </p>
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📹</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Upload Videos</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Share product demos, reviews, tutorials and more</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🎬</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Discover Content</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Watch videos from other users and creators</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💬</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Engage & Comment</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Like, comment and interact with video content</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Groups & Communities -->
                <div x-show="currentStep === 6" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">👨‍👩‍👧‍👦</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Groups & Communities
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Join or create groups based on your interests
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🏘️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Join Groups</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Find groups for Electronics, Fashion, Cars, Real Estate & more</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">✨</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Create Groups</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Start your own community and become an admin</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💭</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Group Chat</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Chat with group members and share ideas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 8: Messaging & Chat -->
                <div x-show="currentStep === 7" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">💬</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Direct Messaging
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Chat privately with buyers, sellers, and other users
                    </p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📨</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Private Chat</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Send messages, photos, and files securely</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔔</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Real-time Notifications</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Get instant alerts for new messages</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📎</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Send Attachments</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Share photos, documents, and files in chat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 9: Referral & Earn Money -->
                <div x-show="currentStep === 8" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">💰</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Referral Program - Earn Money!
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Share your referral link and earn money when people sign up
                    </p>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🎁</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Get Your Unique Link</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Every user gets a unique referral link to share</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">👥</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Invite Friends & Family</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Share on WhatsApp, Facebook, Twitter, or copy the link</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💵</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Earn Commission</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Get paid when your referrals sign up and make purchases</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Track Your Earnings</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">See your referrals, clicks, and earnings in your dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 10: Publisher Program -->
                <div x-show="currentStep === 9" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">📺</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Become a Publisher
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Monetize your website or social media by displaying ads
                    </p>
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🌐</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Register as Publisher</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Sign up to display ads on your website or blog</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📋</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Get Ad Codes</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Copy ad codes and place them on your website</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💰</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Earn Per Click</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Get paid when visitors click on ads displayed on your site</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 11: Account Security -->
                <div x-show="currentStep === 10" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🔐</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Account Security Features
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Protect your account with advanced security features
                    </p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔒</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Two-Factor Authentication (2FA)</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Add extra security with TOTP authenticator apps</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Login Notifications</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Get alerts for new logins and suspicious activity</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔑</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Account Recovery</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Request account recovery if you lose access</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🚨</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Report Security Issues</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Report suspicious activity or security concerns</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 12: Admin Dashboard (for admins) -->
                <div x-show="currentStep === 11" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">⚙️</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Admin Dashboard Overview
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Powerful tools for managing the entire platform (Admin Only)
                    </p>
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">👥</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">User Management</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Manage users, verify accounts, handle suspensions</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📝</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Content Moderation</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Review adverts, posts, videos, and reported content</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Analytics & Reports</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">View platform statistics, revenue, and user activity</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔧</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Settings & Configuration</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Manage categories, pricing, payment gateways, and more</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 13: Safety Tips -->
                <div x-show="currentStep === 12" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🛡️</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Stay Safe Online
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Your safety is our priority. Follow these tips
                    </p>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">✅</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Meet in Public Places</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Always meet buyers/sellers in safe, public locations</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔍</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Verify Items Before Payment</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Inspect items carefully before making payment</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🚫</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Report Suspicious Activity</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Use our security reporting system for any concerns</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💳</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Secure Payments</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Use secure payment methods and never share passwords</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 14: Complete -->
                <div x-show="currentStep === 13" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🎉</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        You're All Set!
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        You're ready to start buying and selling on {{ config('app.name') }}
                    </p>
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">What's Next?</h3>
                        <div class="grid md:grid-cols-2 gap-4 text-left">
                            <a href="{{ route('search') }}" class="bg-white/20 hover:bg-white/30 rounded-lg p-4 transition">
                                <span class="text-2xl mb-2 block">🔍</span>
                                <p class="font-semibold">Start Browsing</p>
                                <p class="text-sm text-white/80">Find great deals</p>
                            </a>
                            @auth
                            <a href="{{ route('advertiser.adverts.create') }}" class="bg-white/20 hover:bg-white/30 rounded-lg p-4 transition">
                                <span class="text-2xl mb-2 block">📝</span>
                                <p class="font-semibold">Post Your First Ad</p>
                                <p class="text-sm text-white/80">Start selling today</p>
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="bg-white/20 hover:bg-white/30 rounded-lg p-4 transition">
                                <span class="text-2xl mb-2 block">👤</span>
                                <p class="font-semibold">Create Account</p>
                                <p class="text-sm text-white/80">Join our community</p>
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-12">
                    <button @click="prevStep()"
                            x-show="currentStep > 0"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition font-medium">
                        ← Previous
                    </button>
                    <div x-show="currentStep === 0"></div>

                    <button @click="nextStep()"
                            x-show="currentStep < totalSteps - 1"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg transition font-medium shadow-lg">
                        Next →
                    </button>

                    <button @click="completeTour()"
                            x-show="currentStep === totalSteps - 1"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition font-bold shadow-lg">
                        Get Started! 🚀
                    </button>
                </div>

                <!-- Skip Button -->
                <div class="text-center mt-6">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Skip tutorial
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function onboardingTour() {
    return {
        currentStep: 0,
        totalSteps: 14,

        nextStep() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        async completeTour() {
            try {
                const response = await fetch('{{ route("onboarding.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '{{ route("home") }}';
                }
            } catch (error) {
                console.error('Error:', error);
                window.location.href = '{{ route("home") }}';
            }
        }
    }
}
</script>
@endsection
