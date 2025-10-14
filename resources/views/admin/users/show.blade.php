@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Profile</h1>
            <p class="mt-1 text-gray-600">View and manage user details</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4 shadow-glow">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>

                    <div class="mt-4 flex justify-center gap-2">
                        @if($user->role === 'admin')
                            <span class="badge badge-primary">Admin</span>
                        @else
                            <span class="badge badge-info">Advertiser</span>
                        @endif

                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4 border-t border-gray-200 pt-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">Contact Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-900">{{ $user->email }}</span>
                            </div>
                            @if($user->phone)
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="text-gray-900">{{ $user->phone }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">Account Details</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Member Since</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email Verified</span>
                                    <span class="font-medium text-green-600">✓ Yes</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($user->id !== auth()->id())
                    <div class="border-t border-gray-200 pt-6 mt-6 space-y-3">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                            @csrf
                            <button type="submit" class="w-full btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                                {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full btn btn-danger btn-sm">
                                Delete User
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card bg-gradient-to-br from-blue-50 to-cyan-50 border-l-4 border-blue-500">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $user->adverts->count() }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total Adverts</p>
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $user->adverts->where('status', 'approved')->count() }}</p>
                        <p class="text-sm text-gray-600 mt-1">Approved</p>
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-purple-50 to-pink-50 border-l-4 border-purple-500">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-purple-600">{{ $user->transactions->count() }}</p>
                        <p class="text-sm text-gray-600 mt-1">Transactions</p>
                    </div>
                </div>
            </div>

            <!-- Recent Adverts -->
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Recent Adverts</h3>
                    @if($user->adverts->count() > 5)
                        <a href="{{ route('admin.adverts.index', ['user_id' => $user->id]) }}"
                           class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                            View All →
                        </a>
                    @endif
                </div>

                @if($user->adverts->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->adverts->take(5) as $advert)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                @if($advert->primaryImage)
                                    <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                                         alt="{{ $advert->title }}"
                                         class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $advert->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $advert->category->name }}</p>
                                    <p class="text-sm font-bold text-primary-600 mt-1">₦{{ number_format($advert->price) }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if($advert->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($advert->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($advert->status) }}</span>
                                    @endif

                                    <a href="{{ route('admin.adverts.show', $advert) }}"
                                       class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        View →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-600">No adverts posted yet</p>
                    </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Recent Transactions</h3>
                    @if($user->transactions->count() > 5)
                        <a href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}"
                           class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                            View All →
                        </a>
                    @endif
                </div>

                @if($user->transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->transactions->take(5) as $transaction)
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
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}"
                                               class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <p class="mt-4 text-gray-600">No transactions yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
