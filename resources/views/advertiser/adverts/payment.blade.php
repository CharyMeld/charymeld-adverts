@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900">Choose Your Pricing Plan</h1>
        <p class="text-gray-600 mt-2">Select a plan to publish your advert</p>
    </div>

    <!-- Advert Summary -->
    <div class="card mb-8">
        <h2 class="text-xl font-bold mb-4">Advert Summary</h2>
        <div class="flex items-center">
            @if($advert->primaryImage)
                <img src="{{ asset('storage/' . $advert->primaryImage->image_path) }}"
                     class="w-24 h-24 object-cover rounded-lg">
            @else
                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-3xl">📷</span>
                </div>
            @endif
            <div class="ml-6">
                <h3 class="text-lg font-semibold">{{ $advert->title }}</h3>
                <p class="text-blue-600 font-bold">₦{{ number_format($advert->price) }}</p>
                <p class="text-sm text-gray-600">{{ $advert->location }}</p>
            </div>
        </div>
    </div>

    <!-- Pricing Plans -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Regular Plan -->
        <div class="card border-2 border-gray-200 hover:border-blue-500 transition-colors">
            <div class="text-center">
                <div class="text-4xl mb-3">📄</div>
                <h3 class="text-xl font-bold mb-2">Regular</h3>
                <div class="mb-4">
                    <span class="text-3xl font-bold">₦1,000</span>
                    <span class="text-gray-600">/30 days</span>
                </div>
                <ul class="space-y-2 text-sm text-left mb-6">
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Standard listing</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>30 days active</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Up to 5 images</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Basic support</span>
                    </li>
                </ul>
                <form action="{{ route('payment.paystack.initialize') }}" method="POST" class="payment-form" data-plan="regular">
                    @csrf
                    <input type="hidden" name="advert_id" value="{{ $advert->id }}">
                    <input type="hidden" name="plan" value="regular">
                    <button type="submit" class="w-full btn btn-secondary">
                        Select Plan
                    </button>
                </form>
            </div>
        </div>

        <!-- Featured Plan -->
        <div class="card border-2 border-yellow-500 hover:border-yellow-600 transition-colors relative">
            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    POPULAR
                </span>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">⭐</div>
                <h3 class="text-xl font-bold mb-2">Featured</h3>
                <div class="mb-4">
                    <span class="text-3xl font-bold">₦3,000</span>
                    <span class="text-gray-600">/30 days</span>
                </div>
                <ul class="space-y-2 text-sm text-left mb-6">
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Featured in search results</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Featured badge</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>30 days active</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Up to 5 images</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Priority support</span>
                    </li>
                </ul>
                <form action="{{ route('payment.paystack.initialize') }}" method="POST" class="payment-form" data-plan="featured">
                    @csrf
                    <input type="hidden" name="advert_id" value="{{ $advert->id }}">
                    <input type="hidden" name="plan" value="featured">
                    <button type="submit" class="w-full btn btn-primary bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-300">
                        Select Plan
                    </button>
                </form>
            </div>
        </div>

        <!-- Premium Plan -->
        <div class="card border-2 border-purple-500 hover:border-purple-600 transition-colors">
            <div class="text-center">
                <div class="text-4xl mb-3">💎</div>
                <h3 class="text-xl font-bold mb-2">Premium</h3>
                <div class="mb-4">
                    <span class="text-3xl font-bold">₦5,000</span>
                    <span class="text-gray-600">/30 days</span>
                </div>
                <ul class="space-y-2 text-sm text-left mb-6">
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Top of homepage</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Premium badge</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Homepage featured section</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>30 days active</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Up to 5 images</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>VIP support</span>
                    </li>
                </ul>
                <form action="{{ route('payment.paystack.initialize') }}" method="POST" class="payment-form" data-plan="premium">
                    @csrf
                    <input type="hidden" name="advert_id" value="{{ $advert->id }}">
                    <input type="hidden" name="plan" value="premium">
                    <button type="submit" class="w-full btn btn-primary bg-purple-600 hover:bg-purple-700 focus:ring-purple-300">
                        Select Plan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Gateway Selection -->
    <div class="card">
        <h3 class="font-bold mb-4 text-center text-lg">Select Payment Gateway</h3>
        <p class="text-sm text-gray-600 text-center mb-6">Choose your preferred payment method to proceed</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
            <!-- Paystack Option -->
            <label for="gateway-paystack" class="cursor-pointer">
                <input type="radio" id="gateway-paystack" name="payment_gateway" value="paystack"
                       class="payment-gateway-radio peer hidden" checked>
                <div class="border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-6 text-center transition-all hover:shadow-lg">
                    <div class="text-5xl mb-3">💳</div>
                    <h4 class="font-bold text-lg mb-2">Paystack</h4>
                    <p class="text-sm text-gray-600 mb-2">Secure & Fast</p>
                    <ul class="text-xs text-gray-500 text-left space-y-1">
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>Debit/Credit Cards</span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>Bank Transfer</span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>USSD</span>
                        </li>
                    </ul>
                </div>
            </label>

            <!-- Flutterwave Option -->
            <label for="gateway-flutterwave" class="cursor-pointer">
                <input type="radio" id="gateway-flutterwave" name="payment_gateway" value="flutterwave"
                       class="payment-gateway-radio peer hidden">
                <div class="border-2 border-gray-300 peer-checked:border-orange-600 peer-checked:bg-orange-50 rounded-lg p-6 text-center transition-all hover:shadow-lg">
                    <div class="text-5xl mb-3">🏦</div>
                    <h4 class="font-bold text-lg mb-2">Flutterwave</h4>
                    <p class="text-sm text-gray-600 mb-2">Trusted & Reliable</p>
                    <ul class="text-xs text-gray-500 text-left space-y-1">
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>All Cards</span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>Mobile Money</span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-600 mr-1">✓</span>
                            <span>Bank Transfer</span>
                        </li>
                    </ul>
                </div>
            </label>
        </div>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg max-w-2xl mx-auto">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-900 font-medium">Secure Payment</p>
                    <p class="text-xs text-blue-700 mt-1">
                        All payments are processed securely through our trusted payment partners. Your card details are never stored on our servers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('advertiser.adverts.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Back to My Adverts
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Handle payment gateway selection
    document.addEventListener('DOMContentLoaded', function() {
        const gatewayRadios = document.querySelectorAll('.payment-gateway-radio');
        const paymentForms = document.querySelectorAll('.payment-form');

        // Update form actions when gateway changes
        gatewayRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedGateway = this.value;
                updateFormActions(selectedGateway);
            });
        });

        function updateFormActions(gateway) {
            const routes = {
                'paystack': '{{ route('payment.paystack.initialize') }}',
                'flutterwave': '{{ route('payment.flutterwave.initialize') }}'
            };

            paymentForms.forEach(form => {
                form.action = routes[gateway];
            });

            console.log('Payment gateway changed to:', gateway);
        }

        // Set initial state
        const selectedGateway = document.querySelector('.payment-gateway-radio:checked').value;
        updateFormActions(selectedGateway);
    });
</script>
@endpush

@endsection
