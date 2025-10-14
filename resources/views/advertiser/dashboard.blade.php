@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card bg-blue-50 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Adverts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_adverts'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-green-50 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">✅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Ads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_adverts'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-yellow-50 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">⏳</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_adverts'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-purple-50 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-4xl">👁️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_views'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Adverts -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Recent Adverts</h2>
                <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-primary">
                    + New Ad
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentAdverts as $advert)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        @if($advert->primaryImage)
                            <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-2xl">📷</span>
                            </div>
                        @endif

                        <div class="ml-4 flex-1">
                            <h3 class="font-semibold">{{ Str::limit($advert->title, 40) }}</h3>
                            <p class="text-sm text-gray-600">₦{{ number_format($advert->price) }}</p>
                        </div>

                        <div class="text-right">
                            @if($advert->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($advert->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($advert->status) }}</span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $advert->views }} views</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No adverts yet. <a href="{{ route('advertiser.adverts.create') }}" class="text-primary-600">Create your first ad!</a></p>
                @endforelse
            </div>

            @if($recentAdverts->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('advertiser.adverts.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        View All Adverts →
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <h2 class="text-xl font-bold mb-6">Recent Transactions</h2>

            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold">₦{{ number_format($transaction->amount) }}</p>
                            <p class="text-sm text-gray-600">{{ $transaction->advert->title ?? 'Deleted advert' }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($transaction->status === 'success')
                                <span class="badge badge-success">Success</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Failed</span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ ucfirst($transaction->gateway) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No transactions yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('advertiser.adverts.create') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">📝</span>
            <h3 class="font-bold text-lg">Post New Ad</h3>
            <p class="text-gray-600 text-sm">Create and publish your advert</p>
        </a>

        <a href="{{ route('advertiser.adverts.index') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">📋</span>
            <h3 class="font-bold text-lg">Manage Ads</h3>
            <p class="text-gray-600 text-sm">Edit or delete your adverts</p>
        </a>

        <a href="{{ route('advertiser.messages.index') }}" class="card hover:shadow-lg transition text-center p-6">
            <span class="text-5xl mb-3 block">💬</span>
            <h3 class="font-bold text-lg">Messages</h3>
            <p class="text-gray-600 text-sm">Chat with admin</p>
        </a>
    </div>
</div>
@endsection
