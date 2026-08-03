@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-16 w-auto">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Two-Factor Authentication
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter the verification code from your authenticator app
            </p>
        </div>

        @if(session('message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login.2fa') }}" method="POST">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Authentication Code
                </label>
                <input id="code"
                       name="code"
                       type="text"
                       maxlength="6"
                       pattern="[0-9]{6}"
                       required
                       autofocus
                       class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 text-center text-2xl font-mono tracking-widest"
                       placeholder="000000">
                <p class="mt-2 text-sm text-gray-500">
                    Enter the 6-digit code from your authenticator app
                </p>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    Verify and Login
                </button>
            </div>
        </form>

        <div class="border-t border-gray-300 pt-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Lost your device?</h3>
                <p class="text-sm text-gray-600 mb-3">
                    If you can't access your authenticator app, you can use a recovery code instead.
                </p>
                <button onclick="showRecoveryForm()"
                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    Use recovery code instead
                </button>
            </div>
        </div>

        <div id="recovery-form" class="hidden">
            <form action="{{ route('login.2fa') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="recovery-code" class="block text-sm font-medium text-gray-700 mb-2">
                        Recovery Code
                    </label>
                    <input id="recovery-code"
                           name="code"
                           type="text"
                           maxlength="10"
                           class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center font-mono tracking-wider uppercase"
                           placeholder="XXXXXXXXXX">
                </div>
                <button type="submit"
                        class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200">
                    Verify Recovery Code
                </button>
                <button type="button"
                        onclick="hideRecoveryForm()"
                        class="w-full py-2 text-gray-600 hover:text-gray-800 text-sm">
                    Use authenticator app instead
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Back to login
            </a>
        </div>
    </div>
</div>

<script>
// Auto-submit when 6 digits are entered
document.getElementById('code').addEventListener('input', function(e) {
    if (this.value.length === 6 && /^\d{6}$/.test(this.value)) {
        this.form.submit();
    }
});

function showRecoveryForm() {
    document.querySelector('form').classList.add('hidden');
    document.getElementById('recovery-form').classList.remove('hidden');
}

function hideRecoveryForm() {
    document.getElementById('recovery-form').classList.add('hidden');
    document.querySelector('form').classList.remove('hidden');
}
</script>
@endsection
