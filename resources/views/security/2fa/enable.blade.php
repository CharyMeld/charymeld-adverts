@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Enable Two-Factor Authentication</h1>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-2 text-blue-900">Step 1: Install an Authenticator App</h3>
                    <p class="text-blue-800 mb-2">Download one of these apps on your phone:</p>
                    <ul class="list-disc list-inside text-blue-700 space-y-1">
                        <li>Google Authenticator (iOS/Android)</li>
                        <li>Authy (iOS/Android)</li>
                        <li>Microsoft Authenticator (iOS/Android)</li>
                    </ul>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-3 text-green-900">Step 2: Scan this QR Code</h3>
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-4 rounded-lg border-2 border-green-300 mb-3">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-green-800 text-sm mb-2">Scan this code with your authenticator app</p>
                        <div class="bg-white px-4 py-2 rounded border border-green-300">
                            <p class="text-xs text-gray-600 mb-1">Or enter this code manually:</p>
                            <code class="text-sm font-mono font-semibold text-green-900">{{ $secret }}</code>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-3 text-purple-900">Step 3: Verify the Code</h3>
                    <form action="{{ route('profile.security.2fa.verify') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter the 6-digit code from your app:
                            </label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-center text-2xl font-mono tracking-widest"
                                   placeholder="000000"
                                   required
                                   autofocus>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('profile.security.2fa') }}"
                               class="text-gray-600 hover:text-gray-800 font-medium">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition duration-200">
                                Verify and Enable 2FA
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-yellow-900 mb-1">Important!</h4>
                            <p class="text-yellow-800 text-sm">
                                After verifying, you'll receive recovery codes. Save them in a safe place!
                                You'll need them if you lose access to your authenticator app.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit when 6 digits are entered
    document.getElementById('code').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
</script>
@endsection
