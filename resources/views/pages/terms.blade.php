@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Terms & Conditions</h1>
            <p class="text-gray-600 dark:text-gray-400">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    By accessing and using CharyMeld Adverts, you accept and agree to be bound by the terms and
                    provisions of this agreement. CharyMeld Adverts is a comprehensive platform offering classified advertising,
                    campaign management (text and video ads), social networking features (feed, videos, groups, messaging),
                    monetization programs (referral and publisher), and community features. If you do not agree to these terms,
                    please do not use our services.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. User Accounts</h2>
                <p class="text-gray-700 mb-3">
                    When you create an account with us, you must provide accurate, complete, and current information.
                    Failure to do so constitutes a breach of the Terms, which may result in immediate termination
                    of your account.
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>You are responsible for safeguarding your password</li>
                    <li>You must not share your account with others</li>
                    <li>You must notify us immediately of any unauthorized use</li>
                    <li>You must be at least 18 years old to use this service</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">3. Content Posting & Campaigns</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">Users who post advertisements, campaigns, social content, videos, or participate in groups agree to:</p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Provide accurate and truthful information about items, services, or content</li>
                    <li>Not post illegal, fraudulent, misleading, or deceptive content</li>
                    <li>Not post content that infringes on intellectual property, copyright, or trademark rights</li>
                    <li>Not upload videos or images containing prohibited content</li>
                    <li>Comply with all applicable local, state, and national laws</li>
                    <li>Pay applicable fees for premium listings, featured ads, or campaign advertising when required</li>
                    <li>Ensure campaign ads (text and video) meet our advertising guidelines</li>
                    <li>Take responsibility for content shared in social feed, groups, and messages</li>
                    <li>Respect community guidelines and other users' rights</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Prohibited Content</h2>
                <p class="text-gray-700 mb-3">The following types of content are strictly prohibited:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>Illegal goods or services</li>
                    <li>Stolen property</li>
                    <li>Counterfeit items</li>
                    <li>Adult content or services</li>
                    <li>Weapons and ammunition</li>
                    <li>Drugs and drug paraphernalia</li>
                    <li>Content that promotes violence, hatred, or discrimination</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">5. Payment Terms & Monetization Programs</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-2">Advertising Payments</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            All fees for premium listings, featured advertisements, and campaign advertising are non-refundable.
                            Payment must be completed before your advertisement or campaign goes live. We accept payments through
                            Paystack and Flutterwave. We reserve the right to change our pricing at any time with prior notice.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-2">Referral Program</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            Users can earn commissions by referring new users to the platform. Referral earnings will be tracked
                            and paid according to our referral program terms. Fraudulent referral activity will result in account
                            suspension and forfeiture of earnings.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-2">Publisher Program</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            Publishers can earn by displaying ads on their websites or platforms. Earnings are based on impressions
                            and clicks. Publishers must comply with our ad placement guidelines. We reserve the right to suspend
                            publishers engaging in fraudulent clicks or invalid traffic. Payouts are processed monthly with minimum
                            threshold requirements.
                        </p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Content Moderation</h2>
                <p class="text-gray-700">
                    We reserve the right to review, remove, or refuse any content that violates these terms or
                    that we deem inappropriate. We may suspend or terminate accounts that repeatedly violate our
                    policies without prior notice or refund.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">7. Social Features & Community Guidelines</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    Our platform includes social networking features including social feed, video sharing, groups, and direct messaging.
                    When using these features, you agree to:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Treat other users with respect and courtesy</li>
                    <li>Not harass, bully, or threaten other users</li>
                    <li>Not spam or send unsolicited messages</li>
                    <li>Not impersonate other users or entities</li>
                    <li>Respect intellectual property in videos and posts</li>
                    <li>Follow group rules set by group administrators</li>
                    <li>Report inappropriate content or behavior</li>
                    <li>Understand that we may moderate or remove content that violates these guidelines</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">8. User Safety & Security</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    While we strive to maintain a safe platform with features like two-factor authentication (2FA) and login alerts,
                    you are responsible for your own safety when conducting transactions and interacting with others. We recommend:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Enabling two-factor authentication (2FA) for enhanced account security</li>
                    <li>Using strong, unique passwords for your account</li>
                    <li>Meeting in public places for in-person transactions</li>
                    <li>Verifying items before payment</li>
                    <li>Not sharing sensitive personal information in messages or posts</li>
                    <li>Being cautious when clicking links from other users</li>
                    <li>Reporting suspicious activity, scams, or prohibited content immediately</li>
                    <li>Not sharing your account credentials with anyone</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">9. Intellectual Property</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    The CharyMeld Adverts platform and its original content, features, and functionality are owned
                    by CharyMeld and are protected by international copyright, trademark, and other intellectual
                    property laws. User-generated content (ads, posts, videos, messages) remains the property of
                    the respective users, but by posting, you grant us a license to display and distribute that content
                    on our platform.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">10. Limitation of Liability</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-3">
                    CharyMeld Adverts acts as a platform connecting users for various purposes including buying/selling,
                    advertising, social networking, and content sharing. We provide tools and features but:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                    <li>We are not responsible for the quality, safety, legality, or accuracy of items listed</li>
                    <li>We are not liable for damages resulting from transactions between users</li>
                    <li>We are not responsible for user-generated content in social feed, videos, groups, or messages</li>
                    <li>We do not guarantee campaign performance, impressions, or conversions</li>
                    <li>We are not liable for referral or publisher program earnings disputes</li>
                    <li>We are not responsible for interactions between users in social features</li>
                    <li>We make no guarantees about platform uptime or data availability</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">11. Dispute Resolution</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    Any disputes arising from the use of our services (marketplace, campaigns, social features, or
                    monetization programs) should first be reported to our support team at charlesikeseh@gmail.com.
                    If a resolution cannot be reached, disputes will be governed by the laws of Nigeria.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">12. Changes to Terms</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We reserve the right to modify these terms at any time. We will notify users of significant
                    changes via email, push notification, or platform notification. Continued use of the service after changes
                    constitutes acceptance of the new terms.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">13. Contact Information</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    If you have any questions about these Terms & Conditions, please contact us at:
                </p>
                <div class="mt-4 text-gray-700 dark:text-gray-300">
                    <p><strong>Email:</strong> charlesikeseh@gmail.com</p>
                    <p><strong>Phone:</strong> +234 (0) 903 280 8107</p>
                </div>
            </section>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
