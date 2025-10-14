@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Become a Publisher</h1>
            <p class="text-gray-600">Earn money by displaying ads on your website. Get paid 70% of ad revenue!</p>
        </div>

        <form method="POST" action="{{ route('publisher.register.submit') }}" class="space-y-6">
            @csrf

            <!-- Website URL -->
            <div>
                <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">Website URL *</label>
                <input type="url" name="website_url" id="website_url" value="{{ old('website_url') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="https://example.com">
                @error('website_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website Name -->
            <div>
                <label for="website_name" class="block text-sm font-medium text-gray-700 mb-2">Website Name *</label>
                <input type="text" name="website_name" id="website_name" value="{{ old('website_name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="My Awesome Website">
                @error('website_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website Description -->
            <div>
                <label for="website_description" class="block text-sm font-medium text-gray-700 mb-2">Website Description *</label>
                <textarea name="website_description" id="website_description" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Describe your website content, audience, and traffic...">{{ old('website_description') }}</textarea>
                @error('website_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website Category -->
            <div>
                <label for="website_category" class="block text-sm font-medium text-gray-700 mb-2">Website Category *</label>
                <select name="website_category" id="website_category" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select Category</option>
                    <option value="blog" {{ old('website_category') == 'blog' ? 'selected' : '' }}>Blog</option>
                    <option value="news" {{ old('website_category') == 'news' ? 'selected' : '' }}>News</option>
                    <option value="ecommerce" {{ old('website_category') == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                    <option value="entertainment" {{ old('website_category') == 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                    <option value="technology" {{ old('website_category') == 'technology' ? 'selected' : '' }}>Technology</option>
                    <option value="education" {{ old('website_category') == 'education' ? 'selected' : '' }}>Education</option>
                    <option value="health" {{ old('website_category') == 'health' ? 'selected' : '' }}>Health</option>
                    <option value="finance" {{ old('website_category') == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="sports" {{ old('website_category') == 'sports' ? 'selected' : '' }}>Sports</option>
                    <option value="other" {{ old('website_category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('website_category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monthly Visitors -->
            <div>
                <label for="monthly_visitors" class="block text-sm font-medium text-gray-700 mb-2">Monthly Visitors *</label>
                <input type="number" name="monthly_visitors" id="monthly_visitors" value="{{ old('monthly_visitors') }}" required min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="10000">
                @error('monthly_visitors')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <select name="payment_method" id="payment_method" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    onchange="updatePaymentFields()">
                    <option value="">Select Payment Method</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="payoneer" {{ old('payment_method') == 'payoneer' ? 'selected' : '' }}>Payoneer</option>
                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Details (Dynamic based on method) -->
            <div id="payment-details-section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Details *</label>

                <!-- Bank Transfer -->
                <div id="bank-transfer-fields" class="space-y-4 hidden">
                    <input type="text" name="payment_details[bank_name]" placeholder="Bank Name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.bank_name') }}">
                    <input type="text" name="payment_details[account_number]" placeholder="Account Number"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.account_number') }}">
                    <input type="text" name="payment_details[account_name]" placeholder="Account Name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.account_name') }}">
                </div>

                <!-- PayPal -->
                <div id="paypal-fields" class="hidden">
                    <input type="email" name="payment_details[paypal_email]" placeholder="PayPal Email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.paypal_email') }}">
                </div>

                <!-- Payoneer -->
                <div id="payoneer-fields" class="hidden">
                    <input type="email" name="payment_details[payoneer_email]" placeholder="Payoneer Email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.payoneer_email') }}">
                </div>

                <!-- Mobile Money -->
                <div id="mobile-money-fields" class="space-y-4 hidden">
                    <input type="text" name="payment_details[provider]" placeholder="Provider (MTN, Vodafone, etc.)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.provider') }}">
                    <input type="text" name="payment_details[phone_number]" placeholder="Phone Number"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="{{ old('payment_details.phone_number') }}">
                </div>

                @error('payment_details')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('advertiser.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updatePaymentFields() {
    const method = document.getElementById('payment_method').value;
    const section = document.getElementById('payment-details-section');

    // Hide all fields
    document.querySelectorAll('#payment-details-section > div').forEach(div => {
        div.classList.add('hidden');
    });

    if (method) {
        section.classList.remove('hidden');

        if (method === 'bank_transfer') {
            document.getElementById('bank-transfer-fields').classList.remove('hidden');
        } else if (method === 'paypal') {
            document.getElementById('paypal-fields').classList.remove('hidden');
        } else if (method === 'payoneer') {
            document.getElementById('payoneer-fields').classList.remove('hidden');
        } else if (method === 'mobile_money') {
            document.getElementById('mobile-money-fields').classList.remove('hidden');
        }
    } else {
        section.classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updatePaymentFields);
</script>
@endsection
