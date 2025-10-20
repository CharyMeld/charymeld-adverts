@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Two-Factor Authentication</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-semibold text-lg">Two-Factor Authentication Status</h3>
                        <p class="text-gray-600 mt-1">
                            @if($user->two_factor_enabled)
                                <span class="text-green-600 font-medium">Enabled</span>
                                <span class="text-sm text-gray-500">- Your account is protected with 2FA</span>
                            @else
                                <span class="text-red-600 font-medium">Disabled</span>
                                <span class="text-sm text-gray-500">- Enable 2FA for enhanced security</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        @if($user->two_factor_enabled)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-lg mb-2">What is Two-Factor Authentication?</h3>
                    <p class="text-gray-600">
                        Two-factor authentication (2FA) adds an extra layer of security to your account.
                        When enabled, you'll need to enter a code from your authenticator app in addition
                        to your password when logging in.
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-lg mb-2">How it works:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Download an authenticator app (Google Authenticator, Authy, etc.)</li>
                        <li>Scan the QR code we provide with your app</li>
                        <li>Enter the 6-digit code to confirm setup</li>
                        <li>Save your recovery codes in a safe place</li>
                        <li>From now on, you'll need your password and the code from the app to log in</li>
                    </ol>
                </div>

                @if(!$user->two_factor_enabled)
                    <div class="pt-4">
                        <a href="{{ route('profile.security.2fa.enable') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Enable Two-Factor Authentication
                        </a>
                    </div>
                @else
                    <div class="pt-4 space-y-3">
                        <a href="{{ route('profile.security.2fa.recovery-codes') }}"
                           class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200 mr-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            View Recovery Codes
                        </a>

                        <form action="{{ route('profile.security.2fa.disable') }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.');">
                            @csrf
                            <div class="inline-block">
                                <input type="password" name="password" placeholder="Enter your password to disable"
                                       class="px-4 py-3 border border-gray-300 rounded-lg mr-2" required>
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Disable 2FA
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
