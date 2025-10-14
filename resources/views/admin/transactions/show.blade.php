@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
            <p class="mt-1 text-gray-600">View complete transaction information</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Transactions
        </a>
    </div>

    <!-- Transaction Status Card -->
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">₦{{ number_format($transaction->amount) }}</h2>
                <p class="text-sm text-gray-600 mt-1">Transaction Amount</p>
            </div>
            <div>
                @if($transaction->status === 'successful')
                    <span class="badge badge-success text-lg px-4 py-2">✓ Successful</span>
                @elseif($transaction->status === 'pending')
                    <span class="badge badge-warning text-lg px-4 py-2">⏳ Pending</span>
                @else
                    <span class="badge badge-danger text-lg px-4 py-2">✗ Failed</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-600 mb-3">Transaction Information</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Reference</dt>
                        <dd class="font-mono font-semibold text-gray-900">{{ $transaction->reference }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Gateway</dt>
                        <dd class="font-semibold text-gray-900">
                            <span class="badge badge-info">{{ ucfirst($transaction->gateway) }}</span>
                        </dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Type</dt>
                        <dd class="font-semibold text-gray-900">{{ ucfirst($transaction->type) }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Date</dt>
                        <dd class="font-semibold text-gray-900">{{ $transaction->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-600 mb-3">User Information</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Name</dt>
                        <dd class="font-semibold text-gray-900">{{ $transaction->user->name }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Email</dt>
                        <dd class="font-semibold text-gray-900">{{ $transaction->user->email }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600">Phone</dt>
                        <dd class="font-semibold text-gray-900">{{ $transaction->user->phone ?? '-' }}</dd>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('admin.users.show', $transaction->user) }}"
                           class="text-primary-600 hover:text-primary-800 font-medium text-sm">
                            View User Profile →
                        </a>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Related Advert -->
    @if($transaction->advert)
        <div class="card">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Related Advertisement</h3>

            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                @if($transaction->advert->primaryImage)
                    <img src="{{ asset('storage/' . $transaction->advert->primaryImage->image_path) }}"
                         alt="{{ $transaction->advert->title }}"
                         class="w-24 h-24 rounded-lg object-cover flex-shrink-0">
                @else
                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 mb-2">{{ $transaction->advert->title }}</h4>
                    <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $transaction->advert->category->name }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ₦{{ number_format($transaction->advert->price) }}
                        </div>
                        <div class="mt-2">
                            @if($transaction->advert->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($transaction->advert->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($transaction->advert->status) }}</span>
                            @endif
                            <span class="badge badge-info ml-2">{{ ucfirst($transaction->advert->plan) }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.adverts.show', $transaction->advert) }}"
                           class="text-primary-600 hover:text-primary-800 font-medium text-sm">
                            View Advertisement →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Gateway Response -->
    @if($transaction->gateway_response)
        <div class="card mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Gateway Response</h3>
            <div class="bg-gray-50 rounded-xl p-4">
                <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode(json_decode($transaction->gateway_response), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    @endif

    <!-- Timeline -->
    <div class="card mt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Transaction Timeline</h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="font-semibold text-gray-900">Transaction Initiated</p>
                    <p class="text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            @if($transaction->updated_at != $transaction->created_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 {{ $transaction->status === 'successful' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $transaction->status === 'successful' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($transaction->status === 'successful')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                @endif
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="font-semibold text-gray-900">Transaction {{ ucfirst($transaction->status) }}</p>
                        <p class="text-sm text-gray-600">{{ $transaction->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
