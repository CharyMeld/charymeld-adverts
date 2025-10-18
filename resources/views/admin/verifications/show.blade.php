@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Verification Details</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Review user verification information</p>
        </div>
        <a href="{{ route('admin.verifications.index') }}" class="btn btn-secondary btn-sm">
            ← Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- User Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">User Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->user->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->user->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Verification Status</label>
                <p>
                    @if($verification->verification_status === 'verified')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                    @elseif($verification->verification_status === 'pending')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($verification->verification_status === 'rejected')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Incomplete</span>
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Completion</label>
                <div class="flex items-center">
                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $verification->getCompletionPercentage() }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $verification->getCompletionPercentage() }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Personal Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->full_name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->date_of_birth?->format('M d, Y') ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</label>
                <p class="text-gray-900 dark:text-gray-100">{{ ucfirst($verification->gender ?? '-') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->phone_number ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">WhatsApp Number</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->whatsapp_number ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- NIN Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">NIN Information</h2>
            @if($verification->is_nin_verified)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Verified</span>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">NIN</label>
                <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $verification->nin ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">NIN Document</label>
                @if($verification->nin_document)
                    <a href="{{ asset('storage/' . $verification->nin_document) }}" target="_blank" class="text-primary-600 hover:text-primary-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Document
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Not uploaded</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Address Information</h2>
            @if($verification->is_address_verified)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Verified</span>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->address ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">City</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->city ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">State</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->state ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Postal Code</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->postal_code ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Country</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->country ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Proof of Address</label>
                @if($verification->proof_of_address_document)
                    <a href="{{ asset('storage/' . $verification->proof_of_address_document) }}" target="_blank" class="text-primary-600 hover:text-primary-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Document
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Not uploaded</p>
                @endif
            </div>
        </div>
    </div>

    <!-- ID Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">ID Information</h2>
            @if($verification->is_id_verified)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Verified</span>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Type</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->id_type ? ucwords(str_replace('_', ' ', $verification->id_type)) : '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Number</label>
                <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $verification->id_number ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Document</label>
                @if($verification->id_document)
                    <a href="{{ asset('storage/' . $verification->id_document) }}" target="_blank" class="text-primary-600 hover:text-primary-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Document
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Not uploaded</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Bank Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Bank Information</h2>
            @if($verification->is_bank_verified)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Verified</span>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Name</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->bank_name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Number</label>
                <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $verification->account_number ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Name</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->account_name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Business Information (if applicable) -->
    @if($verification->business_name || $verification->business_registration_number || $verification->business_document)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Business Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Business Name</label>
                <p class="text-gray-900 dark:text-gray-100">{{ $verification->business_name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration Number</label>
                <p class="text-gray-900 dark:text-gray-100 font-mono">{{ $verification->business_registration_number ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Business Document</label>
                @if($verification->business_document)
                    <a href="{{ asset('storage/' . $verification->business_document) }}" target="_blank" class="text-primary-600 hover:text-primary-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Document
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Not uploaded</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Rejection Reason (if rejected) -->
    @if($verification->verification_status === 'rejected' && $verification->rejection_reason)
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-red-900 dark:text-red-100 mb-2">Rejection Reason</h2>
        <p class="text-red-700 dark:text-red-300">{{ $verification->rejection_reason }}</p>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($verification->verification_status === 'pending')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>

        <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST" class="mb-4">
            @csrf
            <div class="space-y-3 mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="verify_nin" value="1" checked class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Verify NIN</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="verify_id" value="1" checked class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Verify ID</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="verify_address" value="1" checked class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Verify Address</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="verify_bank" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Verify Bank Details</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to approve this verification?')">
                Approve Verification
            </button>
        </form>

        <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" id="rejection_reason" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-100"
                          required></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this verification?')">
                Reject Verification
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
