@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Advertisement Details</h1>
            <p class="mt-1 text-gray-600">Review and manage advertisement</p>
        </div>
        <a href="{{ route('admin.adverts.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Adverts
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="card">
                @if($advert->images->count() > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($advert->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $advert->title }}"
                                     class="w-full h-64 object-cover rounded-xl">
                                @if($image->is_primary)
                                    <span class="absolute top-3 left-3 badge badge-primary">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="card">
                <div class="mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $advert->title }}</h2>
                            <div class="flex items-center gap-3 flex-wrap">
                                @if($advert->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($advert->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($advert->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($advert->status) }}</span>
                                @endif

                                <span class="badge badge-primary">{{ ucfirst($advert->plan) }}</span>

                                @if($advert->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-primary-600">₦{{ number_format($advert->price) }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <div class="prose max-w-none text-gray-700">
                            {{ $advert->description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-600 mb-3">Information</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Category</dt>
                                    <dd class="font-medium text-gray-900">{{ $advert->category->name }}</dd>
                                </div>
                                @if($advert->location)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Location</dt>
                                        <dd class="font-medium text-gray-900">{{ $advert->location }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Views</dt>
                                    <dd class="font-medium text-gray-900">{{ $advert->views_count }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Posted</dt>
                                    <dd class="font-medium text-gray-900">{{ $advert->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-600 mb-3">Contact</h4>
                            <dl class="space-y-2 text-sm">
                                @if($advert->phone)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Phone</dt>
                                        <dd class="font-medium text-gray-900">{{ $advert->phone }}</dd>
                                    </div>
                                @endif
                                @if($advert->email)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Email</dt>
                                        <dd class="font-medium text-gray-900">{{ $advert->email }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($advert->expires_at)
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Expires on</span>
                                <span class="text-sm font-medium text-gray-900">{{ $advert->expires_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transactions -->
            @if($advert->transactions->count() > 0)
                <div class="card">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Related Transactions</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($advert->transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-mono">{{ $transaction->reference }}</td>
                                        <td class="px-4 py-3 text-sm font-bold">₦{{ number_format($transaction->amount) }}</td>
                                        <td class="px-4 py-3">
                                            @if($transaction->status === 'successful')
                                                <span class="badge badge-success">Successful</span>
                                            @elseif($transaction->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Advertiser Info -->
            <div class="card">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Posted By</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr($advert->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $advert->user->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $advert->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $advert->user) }}"
                   class="btn btn-secondary btn-sm w-full">
                    View Profile
                </a>
            </div>

            <!-- Actions -->
            <div class="card">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($advert->status === 'pending')
                        <form method="POST" action="{{ route('admin.adverts.approve', $advert) }}">
                            @csrf
                            <button type="submit" class="w-full btn btn-success btn-sm">
                                ✓ Approve Advert
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.adverts.reject', $advert) }}"
                              onsubmit="return confirm('Are you sure you want to reject this advert?')">
                            @csrf
                            <button type="submit" class="w-full btn btn-danger btn-sm">
                                ✗ Reject Advert
                            </button>
                        </form>
                    @elseif($advert->status === 'rejected')
                        <form method="POST" action="{{ route('admin.adverts.approve', $advert) }}">
                            @csrf
                            <button type="submit" class="w-full btn btn-success btn-sm">
                                ✓ Approve Advert
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('advert.show', $advert->slug) }}" target="_blank"
                       class="w-full btn btn-secondary btn-sm text-center block">
                        View Public Page
                    </a>

                    <form method="POST" action="{{ route('admin.adverts.destroy', $advert) }}"
                          onsubmit="return confirm('Are you sure you want to delete this advert? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn btn-danger btn-sm">
                            Delete Advert
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Views</span>
                        <span class="text-2xl font-bold text-primary-600">{{ $advert->views_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Images</span>
                        <span class="text-2xl font-bold text-primary-600">{{ $advert->images->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Transactions</span>
                        <span class="text-2xl font-bold text-primary-600">{{ $advert->transactions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
