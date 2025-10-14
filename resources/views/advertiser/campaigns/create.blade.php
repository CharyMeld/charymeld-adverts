@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Ad Campaign</h1>
        <p class="text-gray-600">Set up a targeted advertising campaign with budget controls</p>
    </div>

    <form action="{{ route('advertiser.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Campaign Type -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Campaign Type</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="ad_type" value="classified" checked class="peer sr-only" onchange="toggleAdTypeFields()">
                    <div class="border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-50 rounded-xl p-4 text-center hover:border-primary-400 transition min-h-[160px] flex flex-col justify-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="font-semibold text-gray-900 mb-1">Classified</p>
                        <p class="text-xs text-gray-500 leading-tight">Product listing</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="ad_type" value="banner" class="peer sr-only" onchange="toggleAdTypeFields()">
                    <div class="border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-50 rounded-xl p-4 text-center hover:border-primary-400 transition min-h-[160px] flex flex-col justify-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="font-semibold text-gray-900 mb-1">Banner</p>
                        <p class="text-xs text-gray-500 leading-tight">Image ads</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="ad_type" value="text" class="peer sr-only" onchange="toggleAdTypeFields()">
                    <div class="border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-50 rounded-xl p-4 text-center hover:border-primary-400 transition min-h-[160px] flex flex-col justify-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-semibold text-gray-900 mb-1">Text</p>
                        <p class="text-xs text-gray-500 leading-tight">Text only ads</p>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="ad_type" value="video" class="peer sr-only" onchange="toggleAdTypeFields()">
                    <div class="border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-50 rounded-xl p-4 text-center hover:border-primary-400 transition min-h-[160px] flex flex-col justify-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <p class="font-semibold text-gray-900 mb-1">Video</p>
                        <p class="text-xs text-gray-500 leading-tight">Video ads</p>
                    </div>
                </label>
            </div>
            @error('ad_type')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Basic Information</h2>
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Campaign Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="e.g., Summer Sale 2024">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="Describe your campaign...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="price-field" class="classified-only">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₦)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="10000">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner/Text Ad Fields -->
        <div id="banner-fields" class="bg-white rounded-xl shadow-lg p-6 hidden">
            <h2 class="text-xl font-bold mb-4">Ad Creative</h2>
            <div class="space-y-4">
                <div id="banner-image-field">
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                    <p class="text-sm text-gray-500 mb-2">Recommended sizes: 728x90, 300x250, 336x280</p>
                    <input type="file" name="banner_image" id="banner_image" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('banner_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="banner_url" class="block text-sm font-medium text-gray-700 mb-2">Destination URL *</label>
                    <input type="url" name="banner_url" id="banner_url" value="{{ old('banner_url') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="https://yourwebsite.com/landing-page">
                    @error('banner_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="banner_size" class="block text-sm font-medium text-gray-700 mb-2">Banner Size</label>
                    <select name="banner_size" id="banner_size"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="auto">Auto (Responsive)</option>
                        <option value="728x90">Leaderboard (728x90)</option>
                        <option value="300x250">Medium Rectangle (300x250)</option>
                        <option value="336x280">Large Rectangle (336x280)</option>
                        <option value="160x600">Wide Skyscraper (160x600)</option>
                        <option value="300x600">Half Page (300x600)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Classified Ad Fields -->
        <div id="classified-fields" class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Product Details</h2>
            <div class="space-y-4">
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="Lagos, Nigeria">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', auth()->user()->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', auth()->user()->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Images * (Up to 5 images)</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Budget & Pricing -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Budget & Pricing</h2>
            <div class="space-y-4">
                <div>
                    <label for="pricing_model" class="block text-sm font-medium text-gray-700 mb-2">Pricing Model *</label>
                    <select name="pricing_model" id="pricing_model" required onchange="togglePricingFields()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="flat" {{ old('pricing_model') == 'flat' ? 'selected' : '' }}>Flat Rate (One-time payment)</option>
                        <option value="cpc" {{ old('pricing_model') == 'cpc' ? 'selected' : '' }}>CPC (Cost Per Click)</option>
                        <option value="cpm" {{ old('pricing_model') == 'cpm' ? 'selected' : '' }}>CPM (Cost Per 1000 Impressions)</option>
                        <option value="cpa" {{ old('pricing_model') == 'cpa' ? 'selected' : '' }}>CPA (Cost Per Action)</option>
                    </select>
                    @error('pricing_model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="cost-field" class="hidden">
                        <label for="cost_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="cost-label">Cost Per Click (₦)</span>
                        </label>
                        <input type="number" name="cost_per_unit" id="cost_per_unit" value="{{ old('cost_per_unit', 10) }}" min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="10.00">
                        @error('cost_per_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Total Budget (₦) *</label>
                        <input type="number" name="budget" id="budget" value="{{ old('budget') }}" min="0" step="0.01" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="50000">
                        <p class="text-xs text-gray-500 mt-1">Campaign will pause when budget is exhausted</p>
                        @error('budget')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Targeting Options -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Targeting Options</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Countries</label>
                    <select name="target_countries[]" multiple size="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="NG" {{ in_array('NG', old('target_countries', [])) ? 'selected' : '' }}>Nigeria</option>
                        <option value="GH" {{ in_array('GH', old('target_countries', [])) ? 'selected' : '' }}>Ghana</option>
                        <option value="KE" {{ in_array('KE', old('target_countries', [])) ? 'selected' : '' }}>Kenya</option>
                        <option value="ZA" {{ in_array('ZA', old('target_countries', [])) ? 'selected' : '' }}>South Africa</option>
                        <option value="US" {{ in_array('US', old('target_countries', [])) ? 'selected' : '' }}>United States</option>
                        <option value="GB" {{ in_array('GB', old('target_countries', [])) ? 'selected' : '' }}>United Kingdom</option>
                        <option value="CA" {{ in_array('CA', old('target_countries', [])) ? 'selected' : '' }}>Canada</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple. Leave empty for all countries.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Devices</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="target_devices[]" value="mobile" {{ in_array('mobile', old('target_devices', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Mobile</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_devices[]" value="tablet" {{ in_array('tablet', old('target_devices', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Tablet</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_devices[]" value="desktop" {{ in_array('desktop', old('target_devices', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Desktop</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave empty for all devices</p>
                </div>

                <div>
                    <label for="target_keywords" class="block text-sm font-medium text-gray-700 mb-2">Target Keywords</label>
                    <input type="text" name="target_keywords" id="target_keywords" value="{{ old('target_keywords') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="e.g., laptop, smartphone, electronics (comma separated)">
                    <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-between items-center">
            <a href="{{ route('advertiser.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold">
                Create Campaign & Proceed to Payment
            </button>
        </div>
    </form>
</div>

<script>
function toggleAdTypeFields() {
    const adType = document.querySelector('input[name="ad_type"]:checked').value;
    const bannerFields = document.getElementById('banner-fields');
    const classifiedFields = document.getElementById('classified-fields');
    const priceField = document.getElementById('price-field');

    if (adType === 'classified') {
        classifiedFields.classList.remove('hidden');
        bannerFields.classList.add('hidden');
        priceField.classList.remove('hidden');
    } else {
        classifiedFields.classList.add('hidden');
        bannerFields.classList.remove('hidden');
        priceField.classList.add('hidden');
    }
}

function togglePricingFields() {
    const pricingModel = document.getElementById('pricing_model').value;
    const costField = document.getElementById('cost-field');
    const costLabel = document.getElementById('cost-label');

    if (pricingModel === 'flat') {
        costField.classList.add('hidden');
    } else {
        costField.classList.remove('hidden');
        if (pricingModel === 'cpc') {
            costLabel.textContent = 'Cost Per Click (₦)';
        } else if (pricingModel === 'cpm') {
            costLabel.textContent = 'Cost Per 1000 Impressions (₦)';
        } else if (pricingModel === 'cpa') {
            costLabel.textContent = 'Cost Per Action (₦)';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAdTypeFields();
    togglePricingFields();
});
</script>
@endsection
