@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.security.recovery.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Recovery Requests
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Request Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h1 class="text-2xl font-bold text-white">Account Recovery Request #{{ $request->id }}</h1>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email Address</span>
                                <p class="mt-1 text-gray-900 font-medium">{{ $request->email }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Submitted</span>
                                <p class="mt-1 text-gray-900">{{ $request->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        @if($request->name)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Full Name</h3>
                                <p class="text-gray-900">{{ $request->name }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Description of Issue</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $request->description }}</p>
                            </div>
                        </div>

                        @if($request->alternative_email)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Alternative Email</h3>
                                <p class="text-gray-900">{{ $request->alternative_email }}</p>
                            </div>
                        @endif

                        @if($request->phone)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Phone Number</h3>
                                <p class="text-gray-900">{{ $request->phone }}</p>
                            </div>
                        @endif

                        @if($request->verification_document)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Verification Document</h3>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 mb-2">Document has been uploaded</p>
                                    <a href="{{ Storage::url($request->verification_document) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Document
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Request Metadata</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><strong>IP Address:</strong> {{ $request->ip_address }}</p>
                                <p><strong>User Agent:</strong> {{ $request->user_agent }}</p>
                            </div>
                        </div>

                        @if($request->user)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-green-900 mb-2">Associated Account Found</h3>
                                <div class="space-y-1 text-sm">
                                    <p><strong>User ID:</strong> {{ $request->user->id }}</p>
                                    <p><strong>Name:</strong> {{ $request->user->name }}</p>
                                    <p><strong>Role:</strong> {{ ucfirst($request->user->role) }}</p>
                                    <p><strong>Status:</strong> {{ $request->user->is_active ? 'Active' : 'Inactive' }}</p>
                                    <a href="{{ route('admin.users.show', $request->user->id) }}"
                                       class="text-green-700 hover:text-green-900 inline-block mt-2">
                                        View Full Profile →
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-800">⚠️ No account found with this email address</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Update Request</h2>
                    <form action="{{ route('admin.security.recovery.update', $request->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="investigating" {{ $request->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                      placeholder="Internal notes about this request...">{{ $request->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                            Update Request
                        </button>
                    </form>

                    @if($request->status === 'approved')
                        <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-sm text-green-800">
                                ✓ Password reset link has been sent to {{ $request->email }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Request Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Request Information</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Current Status:</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'investigating' => 'bg-blue-100 text-blue-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>

                        @if($request->handler)
                            <div>
                                <span class="text-gray-500">Handled By:</span>
                                <p class="mt-1 font-medium">{{ $request->handler->name }}</p>
                            </div>
                        @endif

                        @if($request->resolved_at)
                            <div>
                                <span class="text-gray-500">Resolved At:</span>
                                <p class="mt-1">{{ $request->resolved_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif

                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <p class="mt-1">{{ $request->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-900 mb-2">Important</h4>
                    <p class="text-sm text-yellow-800">
                        Verify the user's identity before approving. Approving will send a password reset link to their email.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
