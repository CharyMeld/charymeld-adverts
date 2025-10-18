@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Privacy Policy</h1>
            <p class="text-gray-600 dark:text-gray-400">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Introduction</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    CharyMeld Adverts ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy
                    explains how we collect, use, disclose, and safeguard your information when you use our classified
                    advertising platform.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Information We Collect</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">We collect information that you provide directly to us:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Account Information:</strong> Name, email address, phone number, and password</li>
                    <li><strong>Profile Information:</strong> Optional profile picture, bio, and preferences</li>
                    <li><strong>Advertisement Data:</strong> Product/service details, images, pricing, and location</li>
                    <li><strong>Payment Information:</strong> Processed securely through Paystack and Flutterwave</li>
                    <li><strong>Communications:</strong> Messages sent through our platform</li>
                    <li><strong>Social Login Data:</strong> When you use Google or Facebook login, we receive basic profile information (name, email, profile picture) as permitted by those services</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Information Collected Automatically</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Usage Data:</strong> Pages viewed, time spent, click patterns</li>
                    <li><strong>Device Information:</strong> IP address, browser type, operating system</li>
                    <li><strong>Cookies:</strong> We use cookies to maintain sessions and improve user experience</li>
                    <li><strong>Analytics:</strong> Google Analytics and Meta Pixel for website performance tracking</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. How We Use Your Information</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>To provide and maintain our service</li>
                    <li>To process your advertisements and transactions</li>
                    <li>To communicate with you about your account and listings</li>
                    <li>To send promotional emails (you can opt-out anytime)</li>
                    <li>To improve our platform and user experience</li>
                    <li>To detect and prevent fraud and abuse</li>
                    <li>To comply with legal obligations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Social Login Privacy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    When you use social login (Google or Facebook):
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>We only request basic profile information (name, email, profile picture)</li>
                    <li>We do not access your social media contacts, posts, or private messages</li>
                    <li>We do not post to your social media accounts without your permission</li>
                    <li>You can revoke our access anytime through your Google/Facebook settings</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Information Sharing</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">We do not sell your personal information. We may share your information with:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Other Users:</strong> Your public listings and contact information are visible to potential buyers</li>
                    <li><strong>Service Providers:</strong> Payment processors (Paystack, Flutterwave), hosting providers</li>
                    <li><strong>Analytics Partners:</strong> Google Analytics, Meta Pixel (anonymized data)</li>
                    <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Data Security</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We implement appropriate technical and organizational measures to protect your data, including:
                    encryption, secure servers, access controls, and regular security audits. However, no method of
                    transmission over the Internet is 100% secure.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. Data Retention</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We retain your information for as long as your account is active or as needed to provide services.
                    After account deletion, we may retain certain information for legal compliance, dispute resolution,
                    and fraud prevention purposes.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. Your Rights</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">You have the right to:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Access your personal data</li>
                    <li>Correct inaccurate data</li>
                    <li>Delete your account and data (see <a href="{{ route('data-deletion') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Data Deletion Instructions</a>)</li>
                    <li>Export your data</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Object to data processing</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. Children's Privacy</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    Our service is not intended for users under 18 years of age. We do not knowingly collect personal
                    information from children. If you believe we have collected information from a child, please contact us.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">11. Cookies Policy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">We use the following types of cookies:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Essential Cookies:</strong> Required for website functionality</li>
                    <li><strong>Analytics Cookies:</strong> Help us understand usage patterns</li>
                    <li><strong>Preference Cookies:</strong> Remember your settings (theme, language)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">12. Changes to This Policy</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We may update this Privacy Policy periodically. We will notify you of significant changes via email
                    or through a prominent notice on our website. Your continued use after changes constitutes acceptance.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">13. Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    If you have questions about this Privacy Policy or wish to exercise your rights:
                </p>
                <div class="mt-4 text-gray-700 dark:text-gray-300">
                    <p><strong>Email:</strong> charlesikeseh@gmail.com</p>
                    <p><strong>Phone:</strong> +234 (0) 903 280 8107</p>
                    <p><strong>Data Deletion:</strong> <a href="{{ route('data-deletion') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Click here for instructions</a></p>
                </div>
            </section>
        </div>

        <div class="mt-8 text-center space-x-4">
            <a href="{{ route('terms') }}" class="btn btn-secondary">Terms & Conditions</a>
            <a href="{{ route('data-deletion') }}" class="btn btn-secondary">Data Deletion</a>
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
