@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('messages.auth.register_title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ __('messages.auth.already_have_account') }}
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('messages.auth.login_button') }}
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.contact.name') }}</label>
                    <input id="name" name="name" type="text" required
                           class="input @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}"
                           placeholder="{{ __('messages.contact.name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.auth.email') }}</label>
                    <input id="email" name="email" type="email" required
                           class="input @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}"
                           placeholder="{{ __('messages.auth.email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.contact.phone') }}</label>
                    <input id="phone" name="phone" type="text"
                           class="input @error('phone') border-red-500 @enderror"
                           value="{{ old('phone') }}"
                           placeholder="+234 800 000 0000">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.auth.password') }}</label>
                    <input id="password" name="password" type="password" required
                           class="input @error('password') border-red-500 @enderror"
                           placeholder="{{ __('messages.auth.password') }}">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.auth.confirm_password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="input"
                           placeholder="{{ __('messages.auth.confirm_password') }}">
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        {{ __('messages.common.agree_terms') }} <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-500">{{ __('messages.footer.terms') }}</a>
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full btn btn-primary">
                    {{ __('messages.auth.register_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
