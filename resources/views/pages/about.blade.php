@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About CharyMeld Adverts</h1>
            <p class="text-lg text-gray-600">Your trusted marketplace for buying and selling</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Who We Are</h2>
            <p class="text-gray-700 mb-4">
                CharyMeld Adverts is a leading online marketplace that connects buyers and sellers across Nigeria.
                We provide a safe, secure, and user-friendly platform where you can buy and sell almost anything.
            </p>
            <p class="text-gray-700">
                Whether you're looking for a new car, searching for your dream home, selling electronics,
                or posting a job listing, CharyMeld Adverts makes it easy to reach thousands of potential buyers or find what you need.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-700">
                Our mission is to empower individuals and businesses by providing them with a reliable platform
                to trade goods and services. We strive to make online classifieds simple, accessible, and trustworthy for everyone.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Why Choose Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy to Use</h3>
                        <p class="text-gray-600">Simple and intuitive interface that makes posting and browsing ads a breeze.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure Platform</h3>
                        <p class="text-gray-600">Your safety is our priority. We implement strict security measures to protect our users.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Large Community</h3>
                        <p class="text-gray-600">Join thousands of users who trust CharyMeld for their buying and selling needs.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Fast & Efficient</h3>
                        <p class="text-gray-600">Get your ads live quickly and start connecting with potential buyers immediately.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-primary-50 rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
            <p class="text-gray-700 mb-6">
                Join our community today and experience the easiest way to buy and sell online.
            </p>
            <div class="flex justify-center space-x-4">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Sign In</a>
                @else
                    <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-primary">Post an Ad</a>
                    <a href="{{ route('search') }}" class="btn btn-secondary">Browse Ads</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
