@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Adverts Management</h1>
            <p class="mt-1 text-gray-600">Review and manage all advertisements</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="{{ route('admin.adverts.index') }}"
               class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All Adverts
            </a>
            <a href="{{ route('admin.adverts.index', ['status' => 'pending']) }}"
               class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pending
            </a>
            <a href="{{ route('admin.adverts.index', ['status' => 'approved']) }}"
               class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'approved' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Approved
            </a>
            <a href="{{ route('admin.adverts.index', ['status' => 'rejected']) }}"
               class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'rejected' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Rejected
            </a>
        </nav>
    </div>

    <!-- Search -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.adverts.index') }}" class="flex gap-4">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search adverts..."
                       class="input">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            <a href="{{ route('admin.adverts.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        </form>
    </div>

    <!-- Adverts Grid -->
    @if($adverts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($adverts as $advert)
                <div class="card card-hover overflow-hidden">
                    @if($advert->primaryImage)
                        <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                             alt="{{ $advert->title }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 flex-1">
                                {{ Str::limit($advert->title, 50) }}
                            </h3>
                            @if($advert->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($advert->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </div>

                        <p class="text-2xl font-bold text-primary-600 mb-3">
                            ₦{{ number_format($advert->price) }}
                        </p>

                        <div class="text-sm text-gray-600 mb-3 space-y-1">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $advert->user->name }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $advert->category->name }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.adverts.show', $advert) }}"
                               class="flex-1 btn btn-secondary btn-sm text-center">
                                View Details
                            </a>
                            @if($advert->status === 'pending')
                                <form method="POST" action="{{ route('admin.adverts.approve', $advert) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full btn btn-success btn-sm">
                                        Approve
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            {{ $adverts->links() }}
        </div>
    @else
        <div class="card text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No adverts found</h3>
            <p class="mt-2 text-gray-500">No advertisements match your criteria</p>
        </div>
    @endif
</div>
@endsection
