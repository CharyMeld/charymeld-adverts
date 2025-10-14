@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms & Conditions</h1>
            <p class="text-gray-600">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-700">
                    By accessing and using CharyMeld Adverts, you accept and agree to be bound by the terms and
                    provision of this agreement. If you do not agree to these terms, please do not use our service.
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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Posting Advertisements</h2>
                <p class="text-gray-700 mb-3">Users who post advertisements agree to:</p>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>Provide accurate and truthful information about items or services</li>
                    <li>Not post illegal, fraudulent, or misleading content</li>
                    <li>Not post content that infringes on intellectual property rights</li>
                    <li>Comply with all applicable local, state, and national laws</li>
                    <li>Pay applicable fees for premium listings when required</li>
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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Payment Terms</h2>
                <p class="text-gray-700">
                    All fees for premium listings and featured advertisements are non-refundable. Payment must be
                    completed before your advertisement goes live. We reserve the right to change our pricing at
                    any time with prior notice.
                </p>
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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. User Safety</h2>
                <p class="text-gray-700 mb-3">
                    While we strive to maintain a safe platform, you are responsible for your own safety when
                    conducting transactions. We recommend:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>Meeting in public places for transactions</li>
                    <li>Verifying items before payment</li>
                    <li>Not sharing sensitive personal information</li>
                    <li>Reporting suspicious activity to us immediately</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Intellectual Property</h2>
                <p class="text-gray-700">
                    The CharyMeld Adverts platform and its original content, features, and functionality are owned
                    by CharyMeld and are protected by international copyright, trademark, and other intellectual
                    property laws.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Limitation of Liability</h2>
                <p class="text-gray-700">
                    CharyMeld Adverts acts as a platform connecting buyers and sellers. We are not responsible for
                    the quality, safety, legality, or accuracy of items listed. We are not liable for any damages
                    resulting from transactions between users.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Dispute Resolution</h2>
                <p class="text-gray-700">
                    Any disputes arising from the use of our service should first be reported to our support team.
                    If a resolution cannot be reached, disputes will be governed by the laws of Nigeria.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to Terms</h2>
                <p class="text-gray-700">
                    We reserve the right to modify these terms at any time. We will notify users of significant
                    changes via email or platform notification. Continued use of the service after changes
                    constitutes acceptance of the new terms.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Information</h2>
                <p class="text-gray-700">
                    If you have any questions about these Terms & Conditions, please contact us at:
                </p>
                <div class="mt-4 text-gray-700">
                    <p>Email: support@charymeld.com</p>
                    <p>Phone: +234 (0) 903 280 8107</p>
                </div>
            </section>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
