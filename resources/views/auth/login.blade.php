<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo / Brand -->
        <div class="mb-6">
            <a href="/" class="text-3xl font-bold text-indigo-600">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <!-- Login Card -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                <p class="mt-2 text-sm text-gray-600">Please sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Login Button -->
                <div class="flex items-center justify-center mt-6">
                    <x-primary-button class="w-full justify-center py-2 px-4 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                        {{ __('Sign In') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">New to {{ config('app.name') }}?</span>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center">
                <a href="/" class="text-sm text-gray-600 hover:text-indigo-600 hover:underline">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</body>

</html>