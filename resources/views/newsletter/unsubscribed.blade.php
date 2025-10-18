@extends('layouts.app')

@section('title', 'Unsubscribed from Newsletter')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-10 w-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                You've Been Unsubscribed
            </h2>
            <p class="text-gray-600 mb-6">
                We're sorry to see you go! You have been successfully removed from our newsletter mailing list.
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">What happens now?</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You will no longer receive weekly digest emails from us</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You can still browse and use CharyMeld Adverts normally</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You can resubscribe anytime if you change your mind</span>
                </li>
            </ul>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-blue-900 mb-2">Changed your mind?</h4>
            <p class="text-sm text-blue-700 mb-4">
                You can resubscribe to our newsletter at any time by entering your email below:
            </p>
            <form action="{{ route('newsletter.resubscribe') }}" method="POST" class="space-y-3">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email address"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required
                >
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors font-medium"
                >
                    Resubscribe to Newsletter
                </button>
            </form>
        </div>

        <div class="text-center space-y-4">
            <a href="{{ route('home') }}" class="inline-block text-blue-600 hover:text-blue-700 font-medium">
                ← Back to Homepage
            </a>

            <div class="text-xs text-gray-500">
                If you unsubscribed by mistake or have any questions, please
                <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">contact us</a>.
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-xs text-gray-600 mb-3">
                Help us improve! Tell us why you unsubscribed:
            </p>
            <div class="space-y-2 text-sm text-gray-700">
                <label class="flex items-center justify-start cursor-pointer hover:bg-gray-100 p-2 rounded">
                    <input type="radio" name="reason" class="mr-2" disabled>
                    <span>Too many emails</span>
                </label>
                <label class="flex items-center justify-start cursor-pointer hover:bg-gray-100 p-2 rounded">
                    <input type="radio" name="reason" class="mr-2" disabled>
                    <span>Content not relevant</span>
                </label>
                <label class="flex items-center justify-start cursor-pointer hover:bg-gray-100 p-2 rounded">
                    <input type="radio" name="reason" class="mr-2" disabled>
                    <span>Never signed up</span>
                </label>
                <label class="flex items-center justify-start cursor-pointer hover:bg-gray-100 p-2 rounded">
                    <input type="radio" name="reason" class="mr-2" disabled>
                    <span>Other reason</span>
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-3 italic">
                (Feedback collection - coming soon)
            </p>
        </div>
    </div>
</div>
@endsection
