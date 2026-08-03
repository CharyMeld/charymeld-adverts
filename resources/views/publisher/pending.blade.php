@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Application Under Review</h1>

        @if($profile->status === 'pending')
            <p class="text-lg text-gray-600 mb-6">
                Your publisher application is currently being reviewed by our team. This usually takes 24-48 hours.
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-left mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">Application Details:</h3>
                <ul class="space-y-2 text-blue-800">
                    <li><strong>Website:</strong> {{ $profile->website_name }} ({{ $profile->website_url }})</li>
                    <li><strong>Category:</strong> {{ ucfirst($profile->website_category) }}</li>
                    <li><strong>Monthly Visitors:</strong> {{ number_format($profile->monthly_visitors) }}</li>
                    <li><strong>Submitted:</strong> {{ $profile->created_at->diffForHumans() }}</li>
                </ul>
            </div>
        @elseif($profile->status === 'rejected')
            <p class="text-lg text-red-600 mb-6">
                Unfortunately, your publisher application was not approved.
            </p>
            @if($profile->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-left mb-6">
                    <h3 class="font-semibold text-red-900 mb-2">Rejection Reason:</h3>
                    <p class="text-red-800">{{ $profile->rejection_reason }}</p>
                </div>
            @endif
            <p class="text-gray-600 mb-6">
                You can update your website and reapply after addressing the issues mentioned above.
            </p>
        @endif

        <div class="flex justify-center space-x-4">
            <a href="{{ route('advertiser.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Back to Dashboard
            </a>
            @if($profile->status === 'rejected')
                <a href="{{ route('publisher.register') }}" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Reapply
                </a>
            @endif
        </div>

        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Questions? Contact us at <a href="mailto:support@charymeld.com" class="text-primary-600 hover:underline">support@charymeld.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
