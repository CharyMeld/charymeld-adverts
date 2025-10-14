@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Adverts</h1>
        <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-primary">
            + Post New Ad
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="?status=all" class="py-4 px-1 border-b-2 {{ request('status', 'all') === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All ({{ $stats['total'] }})
            </a>
            <a href="?status=approved" class="py-4 px-1 border-b-2 {{ request('status') === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Active ({{ $stats['approved'] }})
            </a>
            <a href="?status=pending" class="py-4 px-1 border-b-2 {{ request('status') === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pending ({{ $stats['pending'] }})
            </a>
            <a href="?status=rejected" class="py-4 px-1 border-b-2 {{ request('status') === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Rejected ({{ $stats['rejected'] }})
            </a>
            <a href="?status=expired" class="py-4 px-1 border-b-2 {{ request('status') === 'expired' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Expired ({{ $stats['expired'] }})
            </a>
        </nav>
    </div>

    @if($adverts->count() > 0)
        <div class="space-y-4">
            @foreach($adverts as $advert)
                <div class="card flex items-center justify-between p-6">
                    <div class="flex items-center flex-1">
                        @if($advert->primaryImage)
                            <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                 class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-3xl">📷</span>
                            </div>
                        @endif

                        <div class="ml-6 flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold">{{ $advert->title }}</h3>
                                @if($advert->plan === 'premium')
                                    <span class="badge bg-purple-100 text-purple-800">PREMIUM</span>
                                @elseif($advert->plan === 'featured')
                                    <span class="badge bg-yellow-100 text-yellow-800">FEATURED</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-6 text-sm text-gray-600">
                                <span class="font-semibold text-blue-600">₦{{ number_format($advert->price) }}</span>
                                <span>👁️ {{ $advert->views }} views</span>
                                <span>📅 Posted {{ $advert->created_at->diffForHumans() }}</span>
                                @if($advert->expires_at)
                                    <span>⏰ Expires {{ $advert->expires_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            <div class="mt-2">
                                @if($advert->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($advert->status === 'pending')
                                    <span class="badge badge-warning">Pending Review</span>
                                @elseif($advert->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($advert->status === 'draft')
                                    <span class="badge bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </div>

                            @if($advert->status === 'rejected' && $advert->rejection_reason)
                                <p class="mt-2 text-sm text-red-600">
                                    <strong>Reason:</strong> {{ $advert->rejection_reason }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 ml-6">
                        <a href="{{ route('advertiser.adverts.show', $advert) }}"
                           class="text-blue-600 hover:text-blue-700" title="View">
                            👁️
                        </a>
                        <a href="{{ route('advertiser.adverts.edit', $advert) }}"
                           class="text-gray-600 hover:text-gray-700" title="Edit">
                            ✏️
                        </a>
                        <form action="{{ route('advertiser.adverts.destroy', $advert) }}" method="POST"
                              onsubmit="return confirmDelete('Are you sure you want to delete this advert?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">
                                🗑️
                            </button>
                        </form>

                        @if($advert->status === 'draft' || ($advert->status === 'approved' && $advert->expires_at && $advert->expires_at->isPast()))
                            <a href="{{ route('advertiser.adverts.payment', $advert) }}"
                               class="btn btn-success text-sm">
                                💳 Pay to Publish
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $adverts->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <span class="text-6xl mb-4 block">📝</span>
            <h3 class="text-xl font-semibold mb-2">No adverts found</h3>
            <p class="text-gray-600 mb-6">Start by creating your first advert</p>
            <a href="{{ route('advertiser.adverts.create') }}" class="btn btn-primary">
                + Post New Ad
            </a>
        </div>
    @endif
</div>
@endsection
