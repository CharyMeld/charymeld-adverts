@extends('layouts.tour')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4" x-data="advertiserTour()">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Step <span x-text="currentStep + 1"></span> of <span x-text="totalSteps"></span>
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-3 rounded-full transition-all duration-500"
                     :style="`width: ${((currentStep + 1) / totalSteps) * 100}%`"></div>
            </div>
        </div>

        <!-- Tour Steps -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 md:p-12 min-h-[500px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-pink-400/10 to-blue-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <!-- Step 1: Welcome to Advertiser Dashboard -->
                <div x-show="currentStep === 0" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🎯</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Welcome to Your Advertiser Dashboard!
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Let me show you how to manage your adverts and campaigns effectively
                    </p>
                    <div class="grid md:grid-cols-3 gap-4 mt-8">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">📝</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Create Adverts</p>
                        </div>
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">📊</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Track Performance</p>
                        </div>
                        <div class="p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
                            <span class="text-3xl mb-2 block">💰</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Manage Payments</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Dashboard Overview -->
                <div x-show="currentStep === 1" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">📊</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Dashboard Overview
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Your dashboard shows all important metrics at a glance
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📈</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Statistics Cards</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">View total adverts, active campaigns, impressions, and clicks</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📅</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">See your latest adverts and their performance</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">⚡</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Quick Actions</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Fast access to common tasks from any page</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Creating Adverts -->
                <div x-show="currentStep === 2" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">✍️</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Creating Your Adverts
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Navigate to "My Adverts" → "Create New Advert"
                    </p>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-6 text-left space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                            <p class="text-gray-800 dark:text-gray-200">Choose advert type (Classified or Campaign)</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                            <p class="text-gray-800 dark:text-gray-200">Fill in details: title, description, category</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                            <p class="text-gray-800 dark:text-gray-200">Upload high-quality images (up to 5)</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                            <p class="text-gray-800 dark:text-gray-200">Select pricing plan and payment method</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">5</div>
                            <p class="text-gray-800 dark:text-gray-200">Review and publish!</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Managing Adverts -->
                <div x-show="currentStep === 3" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">⚙️</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Managing Your Adverts
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Find all your adverts in "My Adverts" section
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">✏️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Edit Adverts</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Update title, description, images, or pricing anytime</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">⏸️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Pause/Resume</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Temporarily pause adverts without deleting them</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">View Analytics</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Track views, clicks, and engagement metrics</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔄</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Renew Expired</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Quickly reactivate expired adverts with one click</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Campaigns -->
                <div x-show="currentStep === 4" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🚀</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Running Campaigns
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Create targeted campaigns for maximum reach
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg p-6">
                            <span class="text-3xl mb-3 block">📺</span>
                            <h3 class="font-bold text-lg mb-2">Text Campaigns</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Text-based adverts shown across the platform</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg p-6">
                            <span class="text-3xl mb-3 block">🎥</span>
                            <h3 class="font-bold text-lg mb-2">Video Campaigns</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Engaging video adverts for higher conversion</p>
                        </div>
                    </div>
                    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            💡 <strong>Pro Tip:</strong> Campaigns have advanced targeting options and detailed analytics
                        </p>
                    </div>
                </div>

                <!-- Step 6: Payments & Transactions -->
                <div x-show="currentStep === 5" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">💳</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Payments & Transactions
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        All your financial information in one place
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💰</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Transaction History</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">View all your payments and invoices</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🔒</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Secure Payments</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">We support Paystack and Flutterwave</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📧</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Email Receipts</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Automatic receipts sent to your email</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Profile & Settings -->
                <div x-show="currentStep === 6" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">👤</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Profile & Settings
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Customize your account settings
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 text-left">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Profile Settings</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>✓ Update profile picture</li>
                                <li>✓ Edit bio and contact info</li>
                                <li>✓ Manage social links</li>
                            </ul>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 text-left">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Security</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>✓ Enable 2FA protection</li>
                                <li>✓ Change password</li>
                                <li>✓ View login activity</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Step 8: Complete -->
                <div x-show="currentStep === 7" x-transition class="text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🎉</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        You're Ready to Advertise!
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Start creating your first advert and reach thousands of potential customers
                    </p>
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">Quick Start Guide</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <a href="{{ route('advertiser.adverts.create') }}" class="bg-white/20 hover:bg-white/30 rounded-lg p-4 transition">
                                <span class="text-2xl mb-2 block">📝</span>
                                <p class="font-semibold">Create Your First Advert</p>
                            </a>
                            <a href="{{ route('advertiser.dashboard') }}" class="bg-white/20 hover:bg-white/30 rounded-lg p-4 transition">
                                <span class="text-2xl mb-2 block">📊</span>
                                <p class="font-semibold">View Dashboard</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center mt-12">
                    <button @click="prevStep()"
                            x-show="currentStep > 0"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition font-medium">
                        ← Previous
                    </button>
                    <div x-show="currentStep === 0"></div>

                    <button @click="nextStep()"
                            x-show="currentStep < totalSteps - 1"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg transition font-medium shadow-lg">
                        Next →
                    </button>

                    <button @click="completeTour()"
                            x-show="currentStep === totalSteps - 1"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition font-bold shadow-lg">
                        Start Advertising! 🚀
                    </button>
                </div>

                <!-- Skip Button -->
                <div class="text-center mt-6">
                    <a href="{{ route('advertiser.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Skip tutorial
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function advertiserTour() {
    return {
        currentStep: 0,
        totalSteps: 8,

        nextStep() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        async completeTour() {
            try {
                const response = await fetch('{{ route("onboarding.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tour_type: 'advertiser' })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '{{ route("advertiser.dashboard") }}';
                }
            } catch (error) {
                console.error('Error:', error);
                window.location.href = '{{ route("advertiser.dashboard") }}';
            }
        }
    }
}
</script>
@endsection
