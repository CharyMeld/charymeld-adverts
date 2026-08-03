@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Partner with CharyMeld Adverts
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            Join our partner program and earn commissions by referring advertisers, publishers, and users to our platform.
        </p>
    </div>

    <!-- Partnership Types -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Affiliate Partners -->
        <div class="card hover:shadow-xl transition-shadow">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🤝</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Affiliate Partners</h3>
                <p class="text-gray-600 dark:text-gray-400">Earn up to 20% commission on referrals</p>
            </div>
            <ul class="space-y-3 text-gray-700 dark:text-gray-300 mb-6">
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>20% recurring commission on first payment</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Real-time tracking dashboard</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Marketing materials provided</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Monthly payouts via bank transfer</span>
                </li>
            </ul>
            <a href="#apply" class="btn btn-primary w-full">Apply Now</a>
        </div>

        <!-- Strategic Partners -->
        <div class="card hover:shadow-xl transition-shadow border-2 border-primary-500">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🚀</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Strategic Partners</h3>
                <p class="text-gray-600 dark:text-gray-400">Long-term collaboration opportunities</p>
            </div>
            <ul class="space-y-3 text-gray-700 dark:text-gray-300 mb-6">
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Co-marketing initiatives</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Revenue sharing agreements</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>API integration support</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Dedicated account manager</span>
                </li>
            </ul>
            <a href="#apply" class="btn btn-primary w-full">Partner with Us</a>
        </div>

        <!-- Media & Bloggers -->
        <div class="card hover:shadow-xl transition-shadow">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">📝</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Media & Bloggers</h3>
                <p class="text-gray-600 dark:text-gray-400">Get featured and earn backlinks</p>
            </div>
            <ul class="space-y-3 text-gray-700 dark:text-gray-300 mb-6">
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Write guest posts with backlinks</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Get featured in our press section</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Access to exclusive news & updates</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Compensation for quality content</span>
                </li>
            </ul>
            <a href="#apply" class="btn btn-primary w-full">Contact Us</a>
        </div>
    </div>

    <!-- Why Partner With Us -->
    <div class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-2xl p-12 mb-16 text-white">
        <h2 class="text-3xl font-bold mb-8 text-center">Why Partner With CharyMeld Adverts?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-5xl font-bold mb-2">50K+</div>
                <div class="text-white/80">Active Users</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold mb-2">10K+</div>
                <div class="text-white/80">Ads Posted Monthly</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold mb-2">99.9%</div>
                <div class="text-white/80">Uptime</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold mb-2">24/7</div>
                <div class="text-white/80">Support</div>
            </div>
        </div>
    </div>

    <!-- Application Form -->
    <div class="max-w-3xl mx-auto" id="apply">
        <div class="card">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Apply for Partnership</h2>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('partners.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="input @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                               class="input @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company/Organization
                        </label>
                        <input type="text" name="company" id="company"
                               class="input"
                               value="{{ old('company') }}">
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Website URL
                        </label>
                        <input type="url" name="website" id="website"
                               class="input"
                               value="{{ old('website') }}">
                    </div>
                </div>

                <div>
                    <label for="partnership_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Partnership Type <span class="text-red-500">*</span>
                    </label>
                    <select name="partnership_type" id="partnership_type" required
                            class="input @error('partnership_type') border-red-500 @enderror">
                        <option value="">Select partnership type</option>
                        <option value="affiliate" {{ old('partnership_type') == 'affiliate' ? 'selected' : '' }}>Affiliate Partner</option>
                        <option value="strategic" {{ old('partnership_type') == 'strategic' ? 'selected' : '' }}>Strategic Partner</option>
                        <option value="media" {{ old('partnership_type') == 'media' ? 'selected' : '' }}>Media/Blogger</option>
                    </select>
                    @error('partnership_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tell us about your proposal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="6" required
                              class="input @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full btn btn-primary">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-16 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Frequently Asked Questions</h2>
        <div class="space-y-4">
            <details class="card cursor-pointer">
                <summary class="font-semibold text-lg p-6 dark:text-white">
                    How do I track my referrals?
                </summary>
                <div class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    Once approved, you'll get access to a dedicated dashboard where you can track clicks, registrations, conversions, and earnings in real-time.
                </div>
            </details>

            <details class="card cursor-pointer">
                <summary class="font-semibold text-lg p-6 dark:text-white">
                    When do I get paid?
                </summary>
                <div class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    Commissions are paid monthly via bank transfer. Minimum payout is ₦5,000. Payments are processed on the 15th of each month for the previous month's earnings.
                </div>
            </details>

            <details class="card cursor-pointer">
                <summary class="font-semibold text-lg p-6 dark:text-white">
                    What marketing materials do you provide?
                </summary>
                <div class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    We provide banners, text links, email templates, social media graphics, and landing pages. All materials are available in your partner dashboard.
                </div>
            </details>

            <details class="card cursor-pointer">
                <summary class="font-semibold text-lg p-6 dark:text-white">
                    How long does the approval process take?
                </summary>
                <div class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    We review applications within 2-3 business days. You'll receive an email with your decision and next steps.
                </div>
            </details>
        </div>
    </div>
</div>
@endsection
