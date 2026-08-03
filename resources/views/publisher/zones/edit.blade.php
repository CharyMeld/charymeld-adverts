@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Ad Zone</h1>
        <p class="text-gray-600 mt-2">Update zone settings</p>
    </div>

    <form method="POST" action="{{ route('publisher.zones.update', $zone) }}" class="bg-white rounded-xl shadow-lg p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="zone_name" class="block text-sm font-medium text-gray-700 mb-2">Zone Name *</label>
            <input type="text" name="zone_name" id="zone_name" value="{{ old('zone_name', $zone->zone_name) }}" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            @error('zone_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="ad_type" class="block text-sm font-medium text-gray-700 mb-2">Ad Type *</label>
            <select name="ad_type" id="ad_type" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="any" {{ old('ad_type', $zone->ad_type) == 'any' ? 'selected' : '' }}>Any</option>
                <option value="banner" {{ old('ad_type', $zone->ad_type) == 'banner' ? 'selected' : '' }}>Banner Ads Only</option>
                <option value="text" {{ old('ad_type', $zone->ad_type) == 'text' ? 'selected' : '' }}>Text Ads Only</option>
                <option value="video" {{ old('ad_type', $zone->ad_type) == 'video' ? 'selected' : '' }}>Video Ads Only</option>
            </select>
            @error('ad_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="size" class="block text-sm font-medium text-gray-700 mb-2">Ad Size</label>
            <select name="size" id="size"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Auto (Responsive)</option>
                <option value="728x90" {{ old('size', $zone->size) == '728x90' ? 'selected' : '' }}>Leaderboard (728x90)</option>
                <option value="300x250" {{ old('size', $zone->size) == '300x250' ? 'selected' : '' }}>Medium Rectangle (300x250)</option>
                <option value="336x280" {{ old('size', $zone->size) == '336x280' ? 'selected' : '' }}>Large Rectangle (336x280)</option>
                <option value="160x600" {{ old('size', $zone->size) == '160x600' ? 'selected' : '' }}>Wide Skyscraper (160x600)</option>
                <option value="300x600" {{ old('size', $zone->size) == '300x600' ? 'selected' : '' }}>Half Page (300x600)</option>
                <option value="970x250" {{ old('size', $zone->size) == '970x250' ? 'selected' : '' }}>Billboard (970x250)</option>
            </select>
            @error('size')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $zone->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $zone->is_active) ? 'checked' : '' }}
                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
            <label for="is_active" class="ml-2 text-sm text-gray-700">
                Zone is active
            </label>
        </div>

        <div class="flex justify-between pt-6 border-t border-gray-200">
            <form method="POST" action="{{ route('publisher.zones.destroy', $zone) }}" onsubmit="return confirm('Are you sure you want to delete this zone?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete Zone
                </button>
            </form>

            <div class="flex space-x-4">
                <a href="{{ route('publisher.zones.show', $zone) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Update Zone
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
