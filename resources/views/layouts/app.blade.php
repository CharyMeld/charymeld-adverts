<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'CharyMeld Adverts') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen transition-colors duration-200">
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-soft sticky top-0 z-50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-extrabold">
                                <span class="bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">CharyMeld</span>
                                <span class="text-gray-700 dark:text-gray-200">Adverts</span>
                            </span>
                        </a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-1">
                        <a href="{{ route('home') }}" class="nav-link inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Home</a>
                        <a href="{{ route('search') }}" class="nav-link inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Search</a>
                        <a href="{{ route('blogs.index') }}" class="nav-link inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Blog</a>
                        <a href="{{ route('about') }}" class="nav-link inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">About</a>
                        <a href="{{ route('contact') }}" class="nav-link inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Contact</a>
                    </div>
                </div>
                <div class="flex items-center space-x-1">
                    @auth
                        <div class="hidden md:flex md:items-center md:space-x-2">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.payouts.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">
                                    Payouts
                                </a>
                                <a href="{{ route('admin.support-chat.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">
                                    Support
                                </a>

                                <!-- Admin Notification Bell -->
                                <div class="relative" id="notificationDropdown">
                                    <button type="button" onclick="toggleNotifications()" class="relative p-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        <span id="notificationBadge" class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">0</span>
                                    </button>

                                    <!-- Notification Dropdown -->
                                    <div id="notificationPanel" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                            <button onclick="markAllAsRead()" class="text-xs text-primary-600 hover:text-primary-700">Mark all read</button>
                                        </div>
                                        <div id="notificationList" class="max-h-96 overflow-y-auto">
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                                Loading notifications...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('advertiser.dashboard') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Dashboard</a>
                                <a href="{{ route('advertiser.analytics.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Analytics</a>

                                @if(auth()->user()->isPublisher())
                                    <a href="{{ route('publisher.dashboard') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm">Publisher</a>
                                    <a href="{{ route('publisher.payouts.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-green-50 text-green-600 text-sm">💰 Withdraw</a>
                                @else
                                    <a href="{{ route('publisher.register') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-green-50 text-green-600 text-sm">💰 Earn</a>
                                @endif

                                <a href="{{ route('advertiser.campaigns.create') }}" class="btn btn-primary btn-sm text-sm">
                                    + Campaign
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors duration-200 font-medium">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="hidden md:flex md:space-x-3">
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
                        </div>
                    @endauth

                    <!-- Theme Toggle Button -->
                    <button type="button" id="theme-toggle" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Toggle theme">
                        <svg class="moon-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="sun-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <button type="button" onclick="toggleMobileMenu()" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Home</a>
                <a href="{{ route('search') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Search Ads</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Admin Panel</a>
                        <a href="{{ route('admin.publishers.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Publishers</a>
                    @else
                        <a href="{{ route('advertiser.dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Dashboard</a>
                        @if(auth()->user()->isPublisher())
                            <a href="{{ route('publisher.dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Publisher Dashboard</a>
                        @else
                            <a href="{{ route('publisher.register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-green-600 hover:bg-green-50">💰 Earn Money - Become Publisher</a>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Login</a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl shadow-soft p-4 flex items-center animate-slide-in">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl shadow-soft p-4 flex items-center animate-slide-in">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <!-- Floating Chatbot Widget -->
    @auth
        <div id="chatbot-widget" class="fixed bottom-6 right-6 z-[9999]">
            <!-- Chat Window (Hidden by default) -->
            <div id="chat-window" class="hidden absolute bottom-20 right-0 w-96 h-[600px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">
                <!-- Chat Header -->
                <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white p-5 flex items-center justify-between shadow-lg">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">CharyMeld Assistant</h3>
                            <p class="text-xs text-primary-100 flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                Online - Here to help!
                            </p>
                        </div>
                    </div>
                    <button onclick="toggleChatWindow()" class="hover:bg-white/20 p-2 rounded-lg transition-all duration-200 hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Chat Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-2.5 animate-fade-in">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="bg-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%] border border-gray-100">
                            <p class="text-gray-800 leading-relaxed">👋 <span class="font-semibold">Hi there!</span> I'm your CharyMeld assistant. How can I help you today?</p>
                        </div>
                    </div>

                    <!-- Suggested Questions -->
                    <div class="space-y-2.5 pt-2">
                        <p class="text-xs font-medium text-gray-500 text-center mb-3">💡 Quick questions you can ask:</p>
                        <button onclick="sendQuickMessage('How do I post an advert?')" class="w-full text-left px-4 py-3 bg-white hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-100 rounded-xl text-sm text-gray-700 hover:text-primary-700 transition-all duration-200 border border-gray-200 hover:border-primary-300 hover:shadow-md transform hover:-translate-y-0.5 flex items-center group">
                            <svg class="w-5 h-5 mr-3 text-primary-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            How do I post an advert?
                        </button>
                        <button onclick="sendQuickMessage('What are your pricing plans?')" class="w-full text-left px-4 py-3 bg-white hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-100 rounded-xl text-sm text-gray-700 hover:text-primary-700 transition-all duration-200 border border-gray-200 hover:border-primary-300 hover:shadow-md transform hover:-translate-y-0.5 flex items-center group">
                            <svg class="w-5 h-5 mr-3 text-primary-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            What are your pricing plans?
                        </button>
                        <button onclick="sendQuickMessage('Is CharyMeld safe?')" class="w-full text-left px-4 py-3 bg-white hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-100 rounded-xl text-sm text-gray-700 hover:text-primary-700 transition-all duration-200 border border-gray-200 hover:border-primary-300 hover:shadow-md transform hover:-translate-y-0.5 flex items-center group">
                            <svg class="w-5 h-5 mr-3 text-primary-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Is CharyMeld safe?
                        </button>
                    </div>
                </div>

                <!-- Typing Indicator (Hidden by default) -->
                <div id="typing-indicator" class="hidden px-5 py-3 bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50">
                    <div class="flex items-start space-x-2.5">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="bg-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md border border-gray-100">
                            <div class="flex space-x-1.5 items-center">
                                <div class="w-2.5 h-2.5 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                <div class="w-2.5 h-2.5 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                <div class="w-2.5 h-2.5 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="p-4 bg-white border-t border-gray-200">
                    <form id="chat-form" class="flex space-x-2">
                        <input type="text" id="chat-input" placeholder="Type your message here..."
                               class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none transition-all duration-200 text-sm"
                               autocomplete="off">
                        <button type="submit" class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white px-4 py-3 rounded-xl hover:shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Chat Button -->
            <button onclick="toggleChatWindow()" id="chat-button"
                    class="relative w-16 h-16 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 hover:from-primary-700 hover:via-primary-800 hover:to-primary-900 text-white rounded-full shadow-2xl hover:shadow-primary-500/50 flex items-center justify-center transform hover:scale-110 transition-all duration-300 group">
                <svg class="w-8 h-8 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <!-- Notification Badge -->
                <span id="chat-notification" class="hidden absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse shadow-lg border-2 border-white">1</span>
                <!-- Online Indicator -->
                <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 rounded-full border-2 border-white animate-pulse"></span>
            </button>
        </div>

        <script>
            let conversationId = null;
            let isWindowOpen = false;

            // Debug: Log when script loads
            console.log('Chatbot script loaded, conversationId:', conversationId);

            function toggleChatWindow() {
                const chatWindow = document.getElementById('chat-window');
                const chatButton = document.getElementById('chat-button');
                isWindowOpen = !isWindowOpen;

                if (isWindowOpen) {
                    chatWindow.classList.remove('hidden');
                    chatButton.classList.add('rotate-180');
                    hideChatNotification(); // Clear notification badge
                    if (!conversationId) {
                        createConversation();
                    } else if (conversationId) {
                        // Only resume polling if we have a valid conversation ID
                        startMessagePolling();
                    }
                } else {
                    chatWindow.classList.add('hidden');
                    chatButton.classList.remove('rotate-180');
                    // Stop polling when window closes
                    stopMessagePolling();
                }
            }

            function createConversation() {
                fetch('{{ route('chatbot.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ personality: 'helpful' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.conversation_id) {
                        conversationId = data.conversation_id;
                        // Start polling for new messages only after conversation is created
                        startMessagePolling();
                    }
                })
                .catch(error => {
                    console.error('Error creating conversation:', error);
                });
            }

            function sendQuickMessage(message) {
                // If no conversation, create one first
                if (!conversationId) {
                    console.log('Creating conversation before sending quick message...');
                    fetch('{{ route('chatbot.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ personality: 'helpful' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.conversation_id) {
                            conversationId = data.conversation_id;
                            console.log('Conversation created, ID:', conversationId);
                            // Start polling
                            startMessagePolling();
                            // Now send the message directly
                            sendMessageToServer(message);
                        }
                    })
                    .catch(error => {
                        console.error('Error creating conversation:', error);
                    });
                } else {
                    // Conversation exists, just send the message
                    sendMessageToServer(message);
                }
            }

            function sendMessageToServer(message) {
                if (!conversationId) {
                    console.error('Cannot send message: no conversation ID');
                    return;
                }

                // Add user message to chat
                addMessageToChat(message, 'user');

                // Show typing indicator
                showTypingIndicator();

                // Send message to server
                fetch(`/assistant/conversations/${conversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    hideTypingIndicator();
                    if (data.success) {
                        // Update lastMessageId to prevent duplicates
                        if (data.user_message && data.user_message.id > lastMessageId) {
                            lastMessageId = data.user_message.id;
                        }

                        // Check if connected to support
                        if (data.support_connected) {
                            updateHeaderForSupport();
                        } else if (data.ai_message) {
                            addMessageToChat(data.ai_message.message, 'ai');
                            // Update lastMessageId to prevent polling from adding this AI message again
                            if (data.ai_message.id > lastMessageId) {
                                lastMessageId = data.ai_message.id;
                            }
                        }

                        // Update UI based on support status
                        if (data.support_status === 'requested') {
                            updateHeaderForSupportRequested();
                            currentSupportStatus = 'requested';
                        } else if (data.support_status === 'connected') {
                            updateHeaderForSupport();
                            currentSupportStatus = 'connected';
                        }
                    }
                })
                .catch(error => {
                    hideTypingIndicator();
                    console.error('Error:', error);
                });
            }

            document.getElementById('chat-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('chat-input');
                const message = input.value.trim();

                if (!message) {
                    return;
                }

                if (!conversationId) {
                    console.warn('No conversation ID, creating conversation first...');
                    createConversation();
                    return;
                }

                // Clear input and send message
                input.value = '';
                sendMessageToServer(message);
            });

            function addMessageToChat(message, sender) {
                const messagesContainer = document.getElementById('chat-messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'animate-fade-in';

                if (sender === 'user') {
                    messageDiv.innerHTML = `
                        <div class="flex items-start space-x-2.5 justify-end">
                            <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-md max-w-[80%]">
                                <p class="leading-relaxed">${escapeHtml(message)}</p>
                            </div>
                            <div class="w-9 h-9 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    `;
                } else if (sender === 'support') {
                    // Support agent message (human)
                    messageDiv.innerHTML = `
                        <div class="flex items-start space-x-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="bg-green-50 rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%] border-2 border-green-200">
                                <p class="text-xs text-green-600 font-semibold mb-1">Support Team</p>
                                <p class="text-gray-800 leading-relaxed">${escapeHtml(message)}</p>
                            </div>
                        </div>
                    `;
                } else {
                    // AI message
                    messageDiv.innerHTML = `
                        <div class="flex items-start space-x-2.5">
                            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div class="bg-white rounded-2xl rounded-tl-sm px-5 py-3 shadow-md max-w-[80%] border border-gray-100">
                                <p class="text-gray-800 leading-relaxed">${escapeHtml(message)}</p>
                            </div>
                        </div>
                    `;
                }

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function showTypingIndicator() {
                document.getElementById('typing-indicator').classList.remove('hidden');
                const messagesContainer = document.getElementById('chat-messages');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function hideTypingIndicator() {
                document.getElementById('typing-indicator').classList.add('hidden');
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function updateHeaderForSupportRequested() {
                const header = document.querySelector('#chat-window > div:first-child > div:first-child > div:nth-child(2)');
                if (header) {
                    header.innerHTML = `
                        <h3 class="font-bold text-lg">CharyMeld Assistant</h3>
                        <p class="text-xs text-yellow-200 flex items-center">
                            <span class="w-2 h-2 bg-yellow-300 rounded-full mr-2 animate-pulse"></span>
                            Connecting to support team...
                        </p>
                    `;
                }
            }

            function updateHeaderForSupport() {
                const header = document.querySelector('#chat-window > div:first-child > div:first-child > div:nth-child(2)');
                if (header) {
                    header.innerHTML = `
                        <h3 class="font-bold text-lg">Live Support Team</h3>
                        <p class="text-xs text-green-200 flex items-center">
                            <span class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></span>
                            Connected - Real person helping you!
                        </p>
                    `;
                }
            }

            // Poll for new messages every 3 seconds when chat is open
            let pollInterval;
            let lastMessageId = 0;
            let currentSupportStatus = 'ai_only';

            function startMessagePolling() {
                console.log('=== STARTING MESSAGE POLLING ===');
                console.log('Conversation ID:', conversationId);
                console.log('Is window open:', isWindowOpen);
                console.log('Polling URL:', `/assistant/conversations/${conversationId}/messages`);

                // Stop any existing polling first
                if (pollInterval) {
                    clearInterval(pollInterval);
                }

                // Don't start polling if we don't have a conversation
                if (!conversationId) {
                    console.warn('Cannot start polling: no conversationId');
                    return;
                }

                pollInterval = setInterval(() => {
                    // Only poll if window is open AND we have a conversation ID
                    if (!isWindowOpen || !conversationId) {
                        return;
                    }

                    fetch(`/assistant/conversations/${conversationId}/messages`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch messages');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('User polling response:', data);
                        console.log('User last message ID:', lastMessageId);
                        console.log('All message IDs:', data.messages.map(m => `ID: ${m.id}, type: ${m.sender_type}`));

                        if (data.success && data.messages) {
                            // Get new messages that we haven't displayed yet
                            const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                            console.log('User new messages:', newMessages);
                            console.log('Filtered IDs:', newMessages.map(m => m.id));

                            newMessages.forEach(msg => {
                                // Add ALL new messages (user, ai, support) that we haven't seen
                                // But skip user messages since we already added them when sending
                                if (msg.sender_type === 'support') {
                                    console.log('User adding support message:', msg);
                                    addMessageToChat(msg.message, 'support');

                                    // Show notification if window is closed
                                    if (!isWindowOpen) {
                                        showChatNotification();
                                    }
                                } else if (msg.sender_type === 'ai') {
                                    console.log('User adding AI message from polling:', msg);
                                    addMessageToChat(msg.message, 'ai');
                                } else {
                                    console.log('User skipping message:', msg.sender_type);
                                }

                                // Always update lastMessageId to highest ID we've seen
                                if (msg.id > lastMessageId) {
                                    lastMessageId = msg.id;
                                }
                            });

                            // Check if support status changed
                            if (data.support_status && data.support_status !== currentSupportStatus) {
                                currentSupportStatus = data.support_status;

                                if (data.support_status === 'connected') {
                                    updateHeaderForSupport();
                                } else if (data.support_status === 'requested') {
                                    updateHeaderForSupportRequested();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Polling error:', error);
                    });
                }, 3000);
            }

            function showChatNotification() {
                const badge = document.getElementById('chat-notification');
                if (badge) {
                    badge.classList.remove('hidden');
                }
            }

            function hideChatNotification() {
                const badge = document.getElementById('chat-notification');
                if (badge) {
                    badge.classList.add('hidden');
                }
            }

            function stopMessagePolling() {
                if (pollInterval) {
                    clearInterval(pollInterval);
                }
            }
        </script>
    @endauth

    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-glow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">CharyMeld</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed">Your trusted marketplace for buying and selling. Connect with thousands of buyers and sellers.</p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6 text-white">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Contact</a></li>
                        <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Terms & Conditions</a></li>
                        <li><a href="{{ route('blogs.index') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6 text-white">Categories</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Vehicles</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Real Estate</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Electronics</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Jobs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6 text-white">Get Started</h4>
                    <ul class="space-y-3">
                        @guest
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Create Account</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Login</a></li>
                        @endguest
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-primary-400 transition-colors duration-200 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Browse Ads</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} <span class="text-primary-400 font-semibold">CharyMeld Adverts</span>. All rights reserved.</p>
                <p class="text-gray-500 text-sm mt-2">Built with ❤️ in Nigeria</p>
            </div>
        </div>
    </footer>

    <!-- Admin Notification System -->
    @if(auth()->check() && auth()->user()->isAdmin())
    <script>
        let notificationPanelOpen = false;

        // Toggle notification panel
        function toggleNotifications() {
            const panel = document.getElementById('notificationPanel');
            notificationPanelOpen = !notificationPanelOpen;

            if (notificationPanelOpen) {
                panel.classList.remove('hidden');
                loadNotifications();
            } else {
                panel.classList.add('hidden');
            }
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown && !dropdown.contains(event.target) && notificationPanelOpen) {
                document.getElementById('notificationPanel').classList.add('hidden');
                notificationPanelOpen = false;
            }
        });

        // Load notifications
        async function loadNotifications() {
            try {
                const response = await fetch('{{ route("admin.notifications.index") }}');
                const data = await response.json();

                const list = document.getElementById('notificationList');

                if (data.notifications.length === 0) {
                    list.innerHTML = `
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-medium">All caught up!</p>
                            <p class="text-sm mt-1">No new notifications</p>
                        </div>
                    `;
                    return;
                }

                list.innerHTML = data.notifications.map(notif => `
                    <div onclick="handleNotificationClick(${notif.id}, '${notif.action_url}')"
                         class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors ${!notif.is_read ? 'bg-primary-50 dark:bg-primary-900/20' : ''}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                ${notif.type === 'support_request' ?
                                    '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>' :
                                    '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                                }
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${notif.title}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${notif.message}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">${formatTime(notif.created_at)}</p>
                            </div>
                            ${!notif.is_read ? '<div class="flex-shrink-0"><span class="w-2 h-2 bg-primary-600 rounded-full inline-block"></span></div>' : ''}
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Handle notification click
        async function handleNotificationClick(id, url) {
            // Mark as read
            try {
                await fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                // Redirect to action URL
                if (url) {
                    window.location.href = url;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Mark all as read
        async function markAllAsRead() {
            try {
                await fetch('{{ route("admin.notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                loadNotifications();
                updateNotificationCount();
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }

        // Update notification count
        async function updateNotificationCount() {
            try {
                const response = await fetch('{{ route("admin.notifications.unread-count") }}');
                const data = await response.json();

                const badge = document.getElementById('notificationBadge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error updating notification count:', error);
            }
        }

        // Format time
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds

            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
            if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
            if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';

            return date.toLocaleDateString();
        }

        // Poll for notifications every 30 seconds
        setInterval(updateNotificationCount, 30000);

        // Initial load
        updateNotificationCount();
    </script>
    @endif
</body>
</html>
