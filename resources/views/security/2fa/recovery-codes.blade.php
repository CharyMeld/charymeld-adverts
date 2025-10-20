@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Recovery Codes</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $codes = session('recovery_codes', $recoveryCodes ?? []);
            @endphp

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Important: Save These Codes</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Store these recovery codes in a safe place. Each code can only be used once.
                               You'll need them if you lose access to your authenticator app.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Your Recovery Codes</h3>
                    <button onclick="copyToClipboard()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition duration-200">
                        Copy All
                    </button>
                </div>

                <div id="codes-container" class="grid grid-cols-2 gap-3">
                    @foreach($codes as $code)
                        <div class="bg-white px-4 py-3 rounded border border-gray-300 font-mono text-center text-lg font-semibold text-gray-800">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex gap-3">
                    <button onclick="printCodes()"
                            class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Codes
                    </button>
                    <button onclick="downloadCodes()"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </button>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-red-900 mb-2">Regenerate Recovery Codes</h3>
                <p class="text-red-800 text-sm mb-3">
                    If you've used all your codes or suspect they've been compromised, you can generate new ones.
                    This will invalidate all existing codes.
                </p>
                <form action="{{ route('profile.security.2fa.recovery-codes.regenerate') }}" method="POST"
                      onsubmit="return confirm('This will invalidate all existing recovery codes. Continue?');">
                    @csrf
                    <div class="flex items-center gap-3">
                        <input type="password"
                               name="password"
                               placeholder="Enter your password"
                               class="flex-1 px-4 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500"
                               required>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200">
                            Regenerate Codes
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-center">
                <a href="{{ route('profile.security.2fa') }}"
                   class="inline-block px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200">
                    Back to Security Settings
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const codes = @json($codes);
    const text = codes.join('\n');
    navigator.clipboard.writeText(text).then(() => {
        alert('Recovery codes copied to clipboard!');
    });
}

function printCodes() {
    const codes = @json($codes);
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Recovery Codes</title>');
    printWindow.document.write('<style>body{font-family:monospace;padding:20px;}h1{margin-bottom:20px;}ul{list-style:none;padding:0;}li{padding:10px;margin:5px 0;background:#f0f0f0;border-radius:5px;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>{{ config("app.name") }} - Recovery Codes</h1>');
    printWindow.document.write('<p>Store these codes in a safe place. Each code can only be used once.</p>');
    printWindow.document.write('<ul>');
    codes.forEach(code => {
        printWindow.document.write(`<li>${code}</li>`);
    });
    printWindow.document.write('</ul>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function downloadCodes() {
    const codes = @json($codes);
    const text = `${document.title} - Recovery Codes\n\n` +
                 'Store these codes in a safe place. Each code can only be used once.\n\n' +
                 codes.join('\n');
    const blob = new Blob([text], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'recovery-codes.txt';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endsection
