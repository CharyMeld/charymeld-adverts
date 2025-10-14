@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <p class="text-gray-600 mt-2">Update user information</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password" id="password" minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password. Minimum 8 characters if changing.</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                    <select name="role" id="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="advertiser" {{ old('role', $user->role) == 'advertiser' ? 'selected' : '' }}>Advertiser</option>
                        <option value="publisher" {{ old('role', $user->role) == 'publisher' ? 'selected' : '' }}>Publisher</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>Admin:</strong> Full platform access<br>
                        <strong>Advertiser:</strong> Can post and manage adverts<br>
                        <strong>Publisher:</strong> Can display ads and earn revenue
                    </p>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Type (for publishers) -->
                <div id="user_type_wrapper" style="display: none;">
                    <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                    <select name="user_type" id="user_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Auto (based on role)</option>
                        <option value="advertiser" {{ old('user_type', $user->user_type) == 'advertiser' ? 'selected' : '' }}>Advertiser Only</option>
                        <option value="publisher" {{ old('user_type', $user->user_type) == 'publisher' ? 'selected' : '' }}>Publisher Only</option>
                        <option value="both" {{ old('user_type', $user->user_type) == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                    @error('user_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Account is active (user can login)
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show/hide user_type field based on role
    document.getElementById('role').addEventListener('change', function() {
        const userTypeWrapper = document.getElementById('user_type_wrapper');
        if (this.value === 'publisher' || this.value === 'advertiser') {
            userTypeWrapper.style.display = 'block';
        } else {
            userTypeWrapper.style.display = 'none';
        }
    });

    // Trigger on page load if role is selected
    if (document.getElementById('role').value !== '') {
        document.getElementById('role').dispatchEvent(new Event('change'));
    }
</script>
@endsection
