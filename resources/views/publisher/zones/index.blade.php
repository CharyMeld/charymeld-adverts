@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Ad Zones</h1>
            <p class="text-gray-600 mt-2">Manage your ad placements</p>
        </div>
        <a href="{{ route('publisher.zones.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Zone
        </a>
    </div>

    @if($zones->isEmpty())
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Ad Zones Yet</h3>
            <p class="text-gray-600 mb-6">Create your first ad zone to start earning money from your website</p>
            <a href="{{ route('publisher.zones.create') }}" class="btn btn-primary">
                Create Your First Zone
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($zones as $zone)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">{{ $zone->zone_name }}</h3>
                            @if($zone->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <span class="text-gray-600">Code:</span>
                                <span class="ml-2 font-mono text-gray-900">{{ $zone->zone_code }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span class="text-gray-600">Type:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ ucfirst($zone->ad_type) }}</span>
                            </div>

                            @if($zone->size)
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    <span class="text-gray-600">Size:</span>
                                    <span class="ml-2 font-medium text-gray-900">{{ $zone->size }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6 py-4 border-y border-gray-200">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Impressions</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($zone->impressions) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Clicks</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($zone->clicks) }}</p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('publisher.zones.show', $zone) }}" class="flex-1 text-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-medium">
                                View Details
                            </a>
                            <a href="{{ route('publisher.zones.edit', $zone) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
