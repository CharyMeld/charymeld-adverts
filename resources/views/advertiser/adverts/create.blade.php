@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Post New Advert</h1>
        <p class="text-gray-600">Fill in the details below to create your advert</p>
    </div>

    <form action="{{ route('advertiser.adverts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Basic Information</h2>

            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" required
                           class="input @error('title') border-red-500 @enderror"
                           value="{{ old('title') }}"
                           placeholder="e.g. Toyota Camry 2020 for Sale">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                    <select name="category_id" id="category_id" required
                            class="input @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
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

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                    <textarea name="description" id="description" required rows="6"
                              class="input @error('description') border-red-500 @enderror"
                              placeholder="Describe your item in detail...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (₦) *</label>
                        <input type="number" name="price" id="price" required min="0"
                               class="input @error('price') border-red-500 @enderror"
                               value="{{ old('price') }}"
                               placeholder="10000">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                        <input type="text" name="location" id="location" required
                               class="input @error('location') border-red-500 @enderror"
                               value="{{ old('location') }}"
                               placeholder="Lagos, Nigeria">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Contact Information</h2>

            <div class="space-y-4">
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700">Contact Name *</label>
                    <input type="text" name="contact_name" id="contact_name" required
                           class="input @error('contact_name') border-red-500 @enderror"
                           value="{{ old('contact_name', auth()->user()->name) }}">
                    @error('contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <input type="text" name="contact_phone" id="contact_phone" required
                               class="input @error('contact_phone') border-red-500 @enderror"
                               value="{{ old('contact_phone', auth()->user()->phone) }}"
                               placeholder="+234 800 000 0000">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                        <input type="email" name="contact_email" id="contact_email"
                               class="input @error('contact_email') border-red-500 @enderror"
                               value="{{ old('contact_email', auth()->user()->email) }}">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Images *</h2>
            <p class="text-sm text-gray-600 mb-4">Upload up to 5 images. First image will be the main image.</p>

            <div>
                <label class="block">
                    <span class="sr-only">Choose images</span>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" required
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100"
                           onchange="previewImages(this)">
                </label>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('advertiser.adverts.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <div class="flex gap-3">
                <button type="submit" name="status" value="draft" class="btn btn-secondary">
                    Save as Draft
                </button>
                <button type="submit" name="status" value="pending" class="btn btn-primary">
                    Continue to Payment
                </button>
            </div>
        </div>

        <p class="text-sm text-gray-600 text-center">
            * After submitting, you'll be redirected to choose a pricing plan and make payment
        </p>
    </form>
</div>
@endsection
