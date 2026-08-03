@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Advert</h1>
        <p class="text-gray-600">Update your advert details</p>
    </div>

    <form action="{{ route('advertiser.adverts.update', $advert) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Basic Information</h2>

            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" required
                           class="input @error('title') border-red-500 @enderror"
                           value="{{ old('title', $advert->title) }}">
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
                                    <option value="{{ $child->id }}"
                                            {{ old('category_id', $advert->category_id) == $child->id ? 'selected' : '' }}>
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
                              class="input @error('description') border-red-500 @enderror">{{ old('description', $advert->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (₦) *</label>
                        <input type="number" name="price" id="price" required min="0"
                               class="input @error('price') border-red-500 @enderror"
                               value="{{ old('price', $advert->price) }}">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                        <input type="text" name="location" id="location" required
                               class="input @error('location') border-red-500 @enderror"
                               value="{{ old('location', $advert->location) }}">
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
                           value="{{ old('contact_name', $advert->contact_name) }}">
                    @error('contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <input type="text" name="contact_phone" id="contact_phone" required
                               class="input @error('contact_phone') border-red-500 @enderror"
                               value="{{ old('contact_phone', $advert->contact_phone) }}">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                        <input type="email" name="contact_email" id="contact_email"
                               class="input @error('contact_email') border-red-500 @enderror"
                               value="{{ old('contact_email', $advert->contact_email) }}">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Images -->
        @if($advert->images->count() > 0)
            <div class="card">
                <h2 class="text-xl font-bold mb-4">Current Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($advert->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 class="w-full h-32 object-cover rounded-lg">
                            @if($image->is_primary)
                                <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    Main
                                </span>
                            @endif
                            <form action="{{ route('advertiser.advert-images.delete', $image) }}" method="POST"
                                  class="absolute top-2 right-2"
                                  onsubmit="return confirmDelete('Delete this image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700">
                                    ×
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Add New Images -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Add New Images</h2>
            <p class="text-sm text-gray-600 mb-4">You can add more images (total max: 5)</p>

            <div>
                <label class="block">
                    <span class="sr-only">Choose images</span>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
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
            <button type="submit" class="btn btn-primary">
                Update Advert
            </button>
        </div>
    </form>
</div>
@endsection
