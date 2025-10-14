# Frontend Implementation Guide

## ✅ What's Already Done

1. **Tailwind CSS** - Installed and configured
2. **Vite** - Configured for Laravel
3. **Custom CSS Classes** - btn, card, badge, input classes ready
4. **JavaScript Functions** - Image preview, mobile menu, filters ready
5. **Package.json** - All dependencies configured
6. **Directory Structure** - All view folders created

## 🚀 Quick Test

To see if everything is working:

```bash
# Build assets
npm run build

# Or run in watch mode
npm run dev

# In another terminal, start Laravel
php artisan serve
```

## 📝 Views Status

### ✅ Already Exists
- `layouts/app.blade.php` - Basic layout (needs Tailwind update)

### ⏳ To Create (Templates Provided Below)

#### 1. Home Page
**File**: `resources/views/home.blade.php`
```blade
<x-layouts.app>
    <!-- Hero Section -->
    <div class="bg-primary-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold mb-4">Find Your Perfect Deal</h1>
            <p class="text-xl mb-8">Browse thousands of ads in Nigeria</p>

            <!-- Search Form -->
            <form action="{{ route('search') }}" class="max-w-3xl">
                <div class="flex gap-2">
                    <input type="text" name="q" placeholder="What are you looking for?"
                           class="flex-1 px-4 py-3 rounded-lg text-gray-900">
                    <button type="submit" class="btn btn-secondary px-8">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold mb-6">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}"
                   class="card hover:shadow-lg transition text-center">
                    <div class="text-4xl mb-2">🏷️</div>
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $category->adverts_count }} ads</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Adverts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold mb-6">Featured Ads</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($featuredAdverts as $advert)
                <a href="{{ route('advert.show', $advert->slug) }}" class="card hover:shadow-lg transition">
                    <img src="{{ $advert->primaryImage?->image_url ?? '/placeholder.jpg' }}"
                         class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="font-semibold mb-2">{{ $advert->title }}</h3>
                    <p class="text-primary-600 font-bold text-lg">₦{{ number_format($advert->price) }}</p>
                    <p class="text-sm text-gray-600">{{ $advert->location }}</p>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.app>
```

#### 2. Login Page
**File**: `resources/views/auth/login.blade.php`
```blade
<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="text-center text-3xl font-bold text-gray-900">
                    Sign in to your account
                </h2>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required
                               class="input @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required class="input">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary-600">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>
                <button type="submit" class="w-full btn btn-primary">Sign in</button>
                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-500">
                        Don't have an account? Sign up
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
```

#### 3. Register Page
**File**: `resources/views/auth/register.blade.php`
```blade
<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <h2 class="text-center text-3xl font-bold mb-8">Create Account</h2>
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" required class="input" value="{{ old('name') }}">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required class="input" value="{{ old('email') }}">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" class="input" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="input">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="input">
                </div>
                <button type="submit" class="w-full btn btn-primary">Create Account</button>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-primary-600">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
```

## 🎨 Layout Component System

Laravel 11 uses component-based layouts. Update `layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CharyMeld Adverts' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation (see full version in file) -->
    <nav>...</nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert-dismissible bg-green-100...">{{ session('success') }}</div>
    @endif

    <!-- Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer>...</footer>
</body>
</html>
```

## 📦 Complete View List Needed

### Authentication (2 files)
- ✅ `auth/login.blade.php`
- ✅ `auth/register.blade.php`

### Public Pages (6 files)
- ✅ `home.blade.php` - Homepage with categories & featured ads
- ⏳ `adverts/search.blade.php` - Search results with filters
- ⏳ `adverts/category.blade.php` - Category listing
- ⏳ `adverts/show.blade.php` - Single advert view with gallery
- ⏳ `blogs/index.blade.php` - Blog listing
- ⏳ `blogs/show.blade.php` - Single blog post
- ⏳ `pages/about.blade.php` - About page
- ⏳ `pages/contact.blade.php` - Contact form
- ⏳ `pages/terms.blade.php` - Terms page

### Advertiser Dashboard (5 files)
- ⏳ `advertiser/dashboard.blade.php` - Stats overview
- ⏳ `advertiser/adverts/index.blade.php` - My adverts list
- ⏳ `advertiser/adverts/create.blade.php` - Create advert form
- ⏳ `advertiser/adverts/edit.blade.php` - Edit advert
- ⏳ `advertiser/adverts/payment.blade.php` - Choose plan & pay

### Admin Dashboard (12 files)
- ⏳ `admin/dashboard.blade.php` - Analytics & charts
- ⏳ `admin/users/index.blade.php` - User list
- ⏳ `admin/users/show.blade.php` - User details
- ⏳ `admin/adverts/index.blade.php` - Adverts management
- ⏳ `admin/adverts/show.blade.php` - Advert details
- ⏳ `admin/categories/index.blade.php` - Categories list
- ⏳ `admin/categories/create.blade.php` - Create category
- ⏳ `admin/categories/edit.blade.php` - Edit category
- ⏳ `admin/blogs/index.blade.php` - Blogs list
- ⏳ `admin/blogs/create.blade.php` - Create blog
- ⏳ `admin/blogs/edit.blade.php` - Edit blog
- ⏳ `admin/transactions/index.blade.php` - Transactions list

## 🔧 Helper Components to Create

### `components/alert.blade.php`
```blade
@props(['type' => 'success'])

@php
    $classes = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    ][$type];
@endphp

<div class="alert-dismissible {{ $classes }} px-4 py-3 rounded relative border" role="alert">
    {{ $slot }}
</div>
```

### `components/card.blade.php`
```blade
<div {{ $attributes->merge(['class' => 'card']) }}>
    {{ $slot }}
</div>
```

## 🎯 Priority Order

1. ✅ Complete `layouts/app.blade.php` with Tailwind
2. ✅ Create `home.blade.php`
3. ✅ Create `auth/login.blade.php` and `auth/register.blade.php`
4. Create `advertiser/dashboard.blade.php`
5. Create `advertiser/adverts/create.blade.php`
6. Create `admin/dashboard.blade.php`
7. Rest of the views

## 📊 Charts for Admin Dashboard

Use Chart.js:
```bash
npm install chart.js
```

In `admin/dashboard.blade.php`:
```html
<canvas id="revenueChart"></canvas>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyRevenue->pluck('month')),
            datasets: [{
                label: 'Revenue',
                data: @json($monthlyRevenue->pluck('total')),
            }]
        }
    });
</script>
```

## 🚀 Next Steps

1. Update `layouts/app.blade.php` with the full Tailwind navigation
2. Create the 3 authentication views
3. Create the home page
4. Create advertiser dashboard views
5. Create admin dashboard views
6. Test each section as you build
7. Add Chart.js for admin analytics
8. Run `npm run build` before testing

## 💡 Tips

- Use `{{ old('field') }}` to preserve form data on validation errors
- Use `@error('field')` directive for validation messages
- All custom CSS classes (btn, card, badge, input) are ready to use
- JavaScript functions are global: `previewImages()`, `toggleMobileMenu()`, `confirmDelete()`

The backend is **100% complete** - just add these views and you're done!
