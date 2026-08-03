@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Account Recovery Request</h1>
                <p class="text-gray-600">Can't access your account? We're here to help!</p>
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

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Before you submit:</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Make sure you've tried the "Forgot Password" option first</li>
                                <li>Provide as much detail as possible to help us verify your identity</li>
                                <li>Our team will review your request within 24-48 hours</li>
                                <li>You'll receive an email update about your request status</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('account-recovery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="your-email@example.com">
                    <p class="mt-1 text-sm text-gray-500">The email address associated with your account</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="John Doe">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Describe Your Issue <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="5"
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide detailed information about your issue (e.g., account hacked, forgot password and email, unable to receive verification emails, etc.)">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimum 20 characters. Be as specific as possible.</p>
                </div>

                <div>
                    <label for="alternative_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alternative Email Address
                    </label>
                    <input type="email"
                           id="alternative_email"
                           name="alternative_email"
                           value="{{ old('alternative_email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="backup-email@example.com">
                    <p class="mt-1 text-sm text-gray-500">We'll use this to contact you if needed</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="+234 XXX XXX XXXX">
                </div>

                <div>
                    <label for="verification_document" class="block text-sm font-medium text-gray-700 mb-2">
                        ID Verification Document (Optional but recommended)
                    </label>
                    <input type="file"
                           id="verification_document"
                           name="verification_document"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">
                        Upload a photo of your ID (passport, driver's license, etc.) to help verify your identity.
                        Max size: 5MB. Formats: PDF, JPG, PNG
                    </p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <strong>Privacy Notice:</strong> Your personal information and documents will be handled
                            securely and only used for account verification purposes. They will be deleted after your
                            request is processed.
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">
                        Back to login
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg">
                        Submit Recovery Request
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center space-y-3">
            <p class="text-sm text-gray-600">
                Need immediate help? Contact our support team at
                <a href="mailto:support@{{ config('app.domain', 'charymeld.com') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    support@{{ config('app.domain', 'charymeld.com') }}
                </a>
            </p>
            <p class="text-sm text-gray-600">
                Or <a href="{{ route('chatbot.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">chat with our AI assistant</a> for instant help
            </p>
        </div>
    </div>
</div>
@endsection
