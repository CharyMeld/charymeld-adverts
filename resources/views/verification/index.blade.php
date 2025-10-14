@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Identity Verification</h1>
        <p class="text-gray-600 mt-2">Complete your identity verification to build trust and secure your account</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($verification && $verification->verification_status === 'pending')
        <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <p class="font-medium">⏳ Your verification is under review</p>
            <p class="text-sm mt-1">Our team is reviewing your submitted information. This usually takes 24-48 hours.</p>
        </div>
    @endif

    @if($verification && $verification->verification_status === 'verified')
        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <p class="font-medium">✅ Your account is verified!</p>
            <p class="text-sm mt-1">Verified on {{ $verification->verified_at->format('M d, Y') }}</p>
        </div>
    @endif

    @if($verification && $verification->verification_status === 'rejected')
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-medium">❌ Verification Rejected</p>
            <p class="text-sm mt-1">{{ $verification->rejection_reason }}</p>
            <p class="text-sm mt-2">Please update your information and resubmit.</p>
        </div>
    @endif

    <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Personal Information -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name (as on NIN) *</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $verification->full_name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('full_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $verification->date_of_birth?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('date_of_birth')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" id="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $verification->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $verification->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $verification->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- National Identification Number (NIN) -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">National Identification (Required)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nin" class="block text-sm font-medium text-gray-700 mb-2">NIN (11 digits) *</label>
                    <input type="text" name="nin" id="nin" maxlength="11" pattern="[0-9]{11}" value="{{ old('nin', $verification->nin ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Your National Identification Number (11 digits)</p>
                    @error('nin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nin_document" class="block text-sm font-medium text-gray-700 mb-2">NIN Slip/Document</label>
                    <input type="file" name="nin_document" id="nin_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Upload NIN slip (PDF, JPG, PNG - Max 2MB)</p>
                    @if($verification && $verification->nin_document)
                        <p class="text-sm text-green-600 mt-1">✓ Document uploaded</p>
                    @endif
                    @error('nin_document')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Contact Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $verification->phone_number ?? auth()->user()->phone ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                    <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $verification->whatsapp_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('whatsapp_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $verification->address ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $verification->city ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                    <input type="text" name="state" id="state" value="{{ old('state', $verification->state ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('state')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $verification->postal_code ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $verification->country ?? 'Nigeria') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Additional ID (Optional) -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Additional ID (Optional)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_type" class="block text-sm font-medium text-gray-700 mb-2">ID Type</label>
                    <select name="id_type" id="id_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select ID Type</option>
                        <option value="drivers_license" {{ old('id_type', $verification->id_type ?? '') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                        <option value="voters_card" {{ old('id_type', $verification->id_type ?? '') == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
                        <option value="international_passport" {{ old('id_type', $verification->id_type ?? '') == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                        <option value="other" {{ old('id_type', $verification->id_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                    <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $verification->id_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="id_document" class="block text-sm font-medium text-gray-700 mb-2">ID Document</label>
                    <input type="file" name="id_document" id="id_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Upload ID document (Max 2MB)</p>
                    @if($verification && $verification->id_document)
                        <p class="text-sm text-green-600 mt-1">✓ Document uploaded</p>
                    @endif
                </div>

                <div>
                    <label for="proof_of_address_document" class="block text-sm font-medium text-gray-700 mb-2">Proof of Address</label>
                    <input type="file" name="proof_of_address_document" id="proof_of_address_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Utility bill, bank statement, etc.</p>
                    @if($verification && $verification->proof_of_address_document)
                        <p class="text-sm text-green-600 mt-1">✓ Document uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Bank Details (Optional)</h2>
            <p class="text-sm text-gray-600 mb-4">Required for refunds and payouts</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $verification->bank_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $verification->account_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                    <input type="text" name="account_name" id="account_name" value="{{ old('account_name', $verification->account_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Business Information (Optional) -->
        <div class="card">
            <h2 class="text-xl font-semibold mb-4">Business Information (Optional)</h2>
            <p class="text-sm text-gray-600 mb-4">For business/corporate advertisers only</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                    <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $verification->business_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="business_registration_number" class="block text-sm font-medium text-gray-700 mb-2">CAC Registration Number</label>
                    <input type="text" name="business_registration_number" id="business_registration_number" value="{{ old('business_registration_number', $verification->business_registration_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="business_document" class="block text-sm font-medium text-gray-700 mb-2">CAC Certificate/Document</label>
                    <input type="file" name="business_document" id="business_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Upload business registration certificate (Max 2MB)</p>
                    @if($verification && $verification->business_document)
                        <p class="text-sm text-green-600 mt-1">✓ Document uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('advertiser.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                {{ $verification && $verification->verification_status !== 'incomplete' ? 'Update & Resubmit' : 'Submit for Verification' }}
            </button>
        </div>
    </form>
</div>
@endsection
