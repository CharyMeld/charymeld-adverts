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
                    explains how we collect, use, disclose, and safeguard your information when you use our comprehensive
                    platform, which includes classified advertising, social networking, campaign management, monetization
                    programs (referral and publisher), and community features.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Information We Collect</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">We collect information that you provide directly to us:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Account Information:</strong> Name, email address, phone number, and password</li>
                    <li><strong>Profile Information:</strong> Optional profile picture, bio, social links, and preferences</li>
                    <li><strong>Advertisement Data:</strong> Classified ads and campaign details (text/video), images, pricing, location, and category</li>
                    <li><strong>Social Content:</strong> Posts on social feed, video uploads, group memberships, and community interactions</li>
                    <li><strong>Payment Information:</strong> Processed securely through Paystack and Flutterwave</li>
                    <li><strong>Communications:</strong> Direct messages, chat conversations, and platform notifications</li>
                    <li><strong>Referral Data:</strong> Referral codes, referral earnings, and referred users</li>
                    <li><strong>Publisher Data:</strong> Ad placements, impressions, clicks, and earnings for publisher program participants</li>
                    <li><strong>Campaign Analytics:</strong> Ad performance data, impressions, clicks, and conversion metrics</li>
                    <li><strong>Social Login Data:</strong> When you use Google or Facebook login, we receive basic profile information (name, email, profile picture) as permitted by those services</li>
                    <li><strong>Security Data:</strong> Two-factor authentication settings, login alerts preferences, and login history</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Information Collected Automatically</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Usage Data:</strong> Pages viewed, time spent, click patterns, ad interactions, video views, and social engagement</li>
                    <li><strong>Device Information:</strong> IP address, browser type, operating system, device model, and screen resolution</li>
                    <li><strong>Cookies:</strong> We use cookies to maintain sessions, remember preferences, and improve user experience</li>
                    <li><strong>Analytics:</strong> Google Analytics and Meta Pixel for website performance tracking and advertising optimization</li>
                    <li><strong>PWA Data:</strong> Service worker cache, offline usage patterns, and push notification preferences</li>
                    <li><strong>Location Data:</strong> Approximate location based on IP address for location-based search and content</li>
                    <li><strong>Performance Metrics:</strong> Ad views, impressions, click-through rates, and campaign performance data</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. How We Use Your Information</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>To provide and maintain our comprehensive platform services (marketplace, social, campaigns)</li>
                    <li>To process your advertisements, campaigns, and transactions</li>
                    <li>To facilitate social networking features (feed, videos, groups, messaging)</li>
                    <li>To manage referral program earnings and publisher program payouts</li>
                    <li>To track and display campaign analytics and performance metrics</li>
                    <li>To communicate with you about your account, listings, and earnings</li>
                    <li>To send push notifications about messages, ads, and platform updates (you can opt-out anytime)</li>
                    <li>To send promotional emails and platform newsletters (you can opt-out anytime)</li>
                    <li>To enable two-factor authentication and security alerts</li>
                    <li>To improve our platform, algorithms, and user experience</li>
                    <li>To detect and prevent fraud, abuse, and prohibited content</li>
                    <li>To provide customer support and respond to inquiries</li>
                    <li>To comply with legal obligations and enforce our terms</li>
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
                    <li><strong>Other Users:</strong> Your public listings, social posts, videos, group content, and contact information are visible to other users based on privacy settings</li>
                    <li><strong>Advertisers:</strong> Campaign performance metrics (anonymized) to help optimize their advertising</li>
                    <li><strong>Publishers:</strong> Ad performance data for publisher program participants to track earnings</li>
                    <li><strong>Referrers:</strong> Information about successful referrals for referral program tracking</li>
                    <li><strong>Service Providers:</strong> Payment processors (Paystack, Flutterwave), hosting providers, email services, and SMS providers</li>
                    <li><strong>Analytics Partners:</strong> Google Analytics, Meta Pixel (anonymized data for tracking and optimization)</li>
                    <li><strong>Social Platforms:</strong> When you use social login or share content to social media</li>
                    <li><strong>Legal Requirements:</strong> When required by law, court order, or to protect our rights and users' safety</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Data Security</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    We implement appropriate technical and organizational measures to protect your data, including:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Encryption of sensitive data in transit and at rest</li>
                    <li>Two-factor authentication (2FA) to secure accounts</li>
                    <li>Login alerts for suspicious activity detection</li>
                    <li>Secure servers with firewalls and intrusion detection</li>
                    <li>Access controls limiting employee access to personal data</li>
                    <li>Regular security audits and vulnerability assessments</li>
                    <li>Secure payment processing through certified providers</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 mt-3">
                    However, no method of transmission over the Internet is 100% secure. We encourage users to enable 2FA and use strong passwords.
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
