@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Publisher Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}! Revenue Share: {{ $profile->revenue_share }}%</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Earnings -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Earnings</p>
                    <p class="text-3xl font-bold">₦{{ number_format($totalEarnings, 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Pending Balance -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Pending Balance</p>
                    <p class="text-3xl font-bold">₦{{ number_format($pendingEarnings, 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>

        <!-- Today's Impressions -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Today's Impressions</p>
                    <p class="text-3xl font-bold">{{ number_format($todayImpressions) }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>

        <!-- Today's Clicks -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Today's Clicks</p>
                    <p class="text-3xl font-bold">{{ number_format($todayClicks) }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('publisher.zones.create') }}" class="bg-white rounded-xl p-6 border-2 border-dashed border-gray-300 hover:border-primary-500 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Create New Zone</h3>
                    <p class="text-sm text-gray-600">Add ad placement</p>
                </div>
            </div>
        </a>

        <a href="{{ route('publisher.zones.index') }}" class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-primary-500 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Manage Zones</h3>
                    <p class="text-sm text-gray-600">{{ $zones->count() }} active zones</p>
                </div>
            </div>
        </a>

        <a href="{{ route('publisher.payouts.index') }}" class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-primary-500 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Request Payout</h3>
                    <p class="text-sm text-gray-600">Min: ₦{{ number_format($profile->minimum_payout, 2) }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Earnings Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Last 30 Days Earnings</h2>
        <div class="h-64 flex items-end space-x-2">
            @forelse($earningsChart as $day)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-primary-500 rounded-t hover:bg-primary-600 transition-colors relative group"
                         style="height: {{ $day->total > 0 ? ($day->total / $earningsChart->max('total')) * 100 : 1 }}%">
                        <div class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                            ₦{{ number_format($day->total, 2) }}
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-center w-full">No earnings data yet</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Earnings & Zones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Earnings -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Recent Earnings</h2>
                <a href="{{ route('publisher.earnings.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentEarnings as $earning)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $earning->advert->title ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $earning->date->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">₦{{ number_format($earning->amount, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $earning->clicks }} clicks</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No earnings yet</p>
                @endforelse
            </div>
        </div>

        <!-- Ad Zones -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Your Ad Zones</h2>
                <a href="{{ route('publisher.zones.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($zones as $zone)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $zone->zone_name }}</p>
                            <p class="text-sm text-gray-500">{{ strtoupper($zone->zone_code) }} - {{ ucfirst($zone->ad_type) }}</p>
                        </div>
                        <div class="text-right">
                            @if($zone->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No zones created yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
