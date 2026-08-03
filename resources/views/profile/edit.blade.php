@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Profile</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ $profile ? route('profile.update') : route('profile.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if($profile)
                @method('PUT')
            @endif

            <!-- Profile Picture/Avatar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                <div class="flex items-center space-x-6">
                    <div class="shrink-0">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="h-24 w-24 object-cover rounded-full border-4 border-white shadow-lg"
                                 id="avatar-preview">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg"
                                 id="avatar-preview">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file"
                               name="avatar"
                               id="avatar-input"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               onchange="previewAvatar(event)">
                        <p class="text-xs text-gray-500 mt-1">Recommended: Square image, max 5MB (JPG, PNG, GIF)</p>
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Display Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Your display name">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Image</label>
                @if($profile && $profile->cover_image)
                    <img src="{{ asset('storage/' . $profile->cover_image) }}" alt="Current cover" class="w-full h-48 object-cover rounded-lg mb-2">
                @endif
                <input type="file"
                       name="cover_image"
                       accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Max 2MB (JPG, PNG)</p>
                @error('cover_image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                <textarea name="bio"
                          rows="4"
                          maxlength="500"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Tell us about yourself...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Max 500 characters</p>
                @error('bio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                <input type="text"
                       name="location"
                       value="{{ old('location', $profile->location ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="City, Country">
                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website</label>
                <input type="url"
                       name="website"
                       value="{{ old('website', $profile->website ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="https://example.com">
                @error('website')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Occupation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Occupation</label>
                <input type="text"
                       name="occupation"
                       value="{{ old('occupation', $profile->occupation ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Your job title">
                @error('occupation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Company -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                <input type="text"
                       name="company"
                       value="{{ old('company', $profile->company ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Company name">
                @error('company')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Birth Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Birth Date</label>
                <input type="date"
                       name="birth_date"
                       value="{{ old('birth_date', $profile && $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                @error('birth_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Social Links -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Social Links</h3>

                <div class="space-y-4">
                    <!-- Facebook -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📘 Facebook</label>
                        <input type="url"
                               name="facebook"
                               value="{{ old('facebook', $profile && $profile->social_links ? ($profile->social_links['facebook'] ?? '') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="https://facebook.com/username">
                    </div>

                    <!-- Twitter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🐦 Twitter/X</label>
                        <input type="url"
                               name="twitter"
                               value="{{ old('twitter', $profile && $profile->social_links ? ($profile->social_links['twitter'] ?? '') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="https://twitter.com/username">
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📷 Instagram</label>
                        <input type="url"
                               name="instagram"
                               value="{{ old('instagram', $profile && $profile->social_links ? ($profile->social_links['instagram'] ?? '') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="https://instagram.com/username">
                    </div>

                    <!-- LinkedIn -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">💼 LinkedIn</label>
                        <input type="url"
                               name="linkedin"
                               value="{{ old('linkedin', $profile && $profile->social_links ? ($profile->social_links['linkedin'] ?? '') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="https://linkedin.com/in/username">
                    </div>
                </div>
            </div>

            <!-- Privacy -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy</label>
                <select name="privacy" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="public" {{ old('privacy', $profile->privacy ?? 'public') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="friends" {{ old('privacy', $profile->privacy ?? '') === 'friends' ? 'selected' : '' }}>Friends Only</option>
                    <option value="private" {{ old('privacy', $profile->privacy ?? '') === 'private' ? 'selected' : '' }}>Private</option>
                </select>
                @error('privacy')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Save Profile
                </button>
                <a href="{{ route('profile.show', auth()->user()->name) }}" class="btn bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-semibold dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(event) {
    const input = event.target;
    const preview = document.getElementById('avatar-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // Replace the preview with actual image
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'h-24 w-24 object-cover rounded-full border-4 border-white shadow-lg';
            img.alt = 'Avatar preview';

            preview.parentNode.replaceChild(img, preview);
            img.id = 'avatar-preview';
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
