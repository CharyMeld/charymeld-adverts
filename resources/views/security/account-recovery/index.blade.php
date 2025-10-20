@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">My Account Recovery Requests</h1>
            <p class="text-gray-600 mt-2">Track the status of your account recovery requests</p>
        </div>

        @if($requests->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Recovery Requests</h3>
                <p class="text-gray-600">You haven't submitted any account recovery requests.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Request ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $request->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'investigating' => 'bg-blue-100 text-blue-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="showDetails({{ $request->id }})"
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Hidden details row -->
                                <tr id="details-{{ $request->id }}" class="hidden bg-gray-50">
                                    <td colspan="5" class="px-6 py-4">
                                        <div class="space-y-3">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Description:</h4>
                                                <p class="text-gray-700">{{ $request->description }}</p>
                                            </div>

                                            @if($request->admin_notes)
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 mb-2">Admin Notes:</h4>
                                                    <p class="text-gray-700">{{ $request->admin_notes }}</p>
                                                </div>
                                            @endif

                                            @if($request->resolved_at)
                                                <div>
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Resolved:</strong> {{ $request->resolved_at->format('M d, Y \a\t h:i A') }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if($request->status === 'approved')
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                    <p class="text-green-800">
                                                        ✓ Your request has been approved! A password reset link has been sent to your email.
                                                    </p>
                                                </div>
                                            @endif

                                            @if($request->status === 'rejected')
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                    <p class="text-red-800">
                                                        Your request was not approved. Please contact support if you believe this is an error.
                                                    </p>
                                                    <p class="text-sm text-red-700 mt-2">
                                                        Need help?
                                                        <a href="mailto:support@{{ config('app.domain', 'charymeld.com') }}" class="font-medium underline">Email us</a>
                                                        or <a href="{{ route('chatbot.index') }}" class="font-medium underline">chat with our AI assistant</a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function showDetails(requestId) {
    const detailsRow = document.getElementById('details-' + requestId);
    if (detailsRow.classList.contains('hidden')) {
        detailsRow.classList.remove('hidden');
    } else {
        detailsRow.classList.add('hidden');
    }
}
</script>
@endsection
