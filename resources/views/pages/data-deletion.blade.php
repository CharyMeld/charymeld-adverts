@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Data Deletion Instructions</h1>
            <p class="text-gray-600 dark:text-gray-400">How to delete your account and personal data</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Account Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    You have the right to delete your account and all associated personal data at any time.
                    We provide multiple ways to request account deletion:
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Method 1: Through Your Account Settings (Recommended)</h2>
                <ol class="list-decimal list-inside text-gray-700 dark:text-gray-300 space-y-3 ml-4">
                    <li>Log in to your CharyMeld Adverts account</li>
                    <li>Navigate to <strong>Dashboard</strong> → <strong>Settings</strong></li>
                    <li>Scroll to the <strong>"Delete Account"</strong> section</li>
                    <li>Click <strong>"Delete My Account"</strong></li>
                    <li>Confirm your decision by entering your password</li>
                    <li>Your account and data will be permanently deleted within 24 hours</li>
                </ol>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Method 2: Email Request</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    If you cannot access your account or prefer email, send a deletion request to:
                </p>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-4">
                    <p class="text-gray-800 dark:text-gray-200"><strong>Email:</strong> charlesikeseh@gmail.com</p>
                    <p class="text-gray-800 dark:text-gray-200 mt-2"><strong>Subject:</strong> Account Deletion Request</p>
                    <p class="text-gray-800 dark:text-gray-200 mt-4"><strong>Include:</strong></p>
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 ml-4 mt-2">
                        <li>Your full name</li>
                        <li>Registered email address</li>
                        <li>Phone number (if provided during registration)</li>
                        <li>Reason for deletion (optional)</li>
                    </ul>
                </div>
                <p class="text-gray-700 dark:text-gray-300">
                    We will process your request within <strong>7 business days</strong> and send you a confirmation email.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Method 3: Social Login Data Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If you signed up using Google or Facebook:
                </p>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Google Account</h3>
                        <ol class="list-decimal list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Go to your <a href="https://myaccount.google.com/permissions" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">Google Account Permissions</a></li>
                            <li>Find "CharyMeld Adverts" in the list</li>
                            <li>Click "Remove Access"</li>
                            <li>Then follow Method 1 or 2 above to delete your account data</li>
                        </ol>
                    </div>

                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Facebook Account</h3>
                        <ol class="list-decimal list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Go to <a href="https://www.facebook.com/settings?tab=applications" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">Facebook Settings → Apps and Websites</a></li>
                            <li>Find "CharyMeld Adverts" in the list</li>
                            <li>Click "Remove"</li>
                            <li>Then follow Method 1 or 2 above to delete your account data</li>
                        </ol>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">What Gets Deleted</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">When you delete your account, we permanently remove:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>✅ Your profile information (name, email, phone, bio)</li>
                    <li>✅ All your active and inactive advertisements</li>
                    <li>✅ Uploaded images and files</li>
                    <li>✅ Message history and conversations</li>
                    <li>✅ Payment history (except as required by law)</li>
                    <li>✅ Preferences and settings</li>
                    <li>✅ Social login associations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">What We Retain (Legal Compliance)</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">For legal, security, and fraud prevention purposes, we may retain:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>⚠️ Transaction records for tax/accounting (anonymized after 7 years)</li>
                    <li>⚠️ Fraud/abuse logs (IP addresses, device fingerprints)</li>
                    <li>⚠️ Legal dispute records (until resolved)</li>
                    <li>⚠️ Backup copies (automatically deleted within 90 days)</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 mt-4">
                    This retained data is isolated, not accessible through our platform, and deleted according to our retention policy.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Timeline</h2>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li><strong>Immediate:</strong> Account access disabled, profile hidden</li>
                        <li><strong>24 hours:</strong> Active data deleted (ads, messages, images)</li>
                        <li><strong>7 days:</strong> Email deletion confirmation sent</li>
                        <li><strong>30 days:</strong> All personal data removed from active systems</li>
                        <li><strong>90 days:</strong> Backup copies automatically purged</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Important Notes</h2>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <ul class="space-y-2 text-gray-800 dark:text-gray-200">
                        <li>⚠️ <strong>Deletion is permanent and cannot be undone</strong></li>
                        <li>⚠️ Active premium listings will not be refunded</li>
                        <li>⚠️ You can create a new account later with the same email</li>
                        <li>⚠️ Public content may be cached by search engines temporarily</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Contact Support</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    If you have questions about data deletion or need assistance:
                </p>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                    <p class="text-gray-800 dark:text-gray-200"><strong>Email:</strong> charlesikeseh@gmail.com</p>
                    <p class="text-gray-800 dark:text-gray-200 mt-2"><strong>Phone:</strong> +234 (0) 903 280 8107</p>
                    <p class="text-gray-800 dark:text-gray-200 mt-2"><strong>Response Time:</strong> Within 48 hours</p>
                </div>
            </section>
        </div>

        <div class="mt-8 text-center space-x-4">
            <a href="{{ route('privacy') }}" class="btn btn-secondary">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="btn btn-secondary">Terms & Conditions</a>
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
