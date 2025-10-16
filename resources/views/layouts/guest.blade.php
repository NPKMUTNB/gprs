<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-indigo-100">
            <!-- Language Switcher -->
            <div class="absolute top-4 right-4">
                <div class="flex gap-2">
                    <a href="{{ url('locale/en') }}" class="px-3 py-1 text-sm rounded-md {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} transition">
                        EN
                    </a>
                    <a href="{{ url('locale/th') }}" class="px-3 py-1 text-sm rounded-md {{ app()->getLocale() == 'th' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} transition">
                        TH
                    </a>
                </div>
            </div>

            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600" />
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="w-full sm:max-w-md mt-6">
                    <x-alert type="success" :message="session('success')" />
                </div>
            @endif

            @if (session('error'))
                <div class="w-full sm:max-w-md mt-6">
                    <x-alert type="error" :message="session('error')" />
                </div>
            @endif

            @if (session('warning'))
                <div class="w-full sm:max-w-md mt-6">
                    <x-alert type="warning" :message="session('warning')" />
                </div>
            @endif

            @if (session('info'))
                <div class="w-full sm:max-w-md mt-6">
                    <x-alert type="info" :message="session('info')" />
                </div>
            @endif

            @if (session('status'))
                <div class="w-full sm:max-w-md mt-6">
                    <x-alert type="success" :message="session('status')" />
                </div>
            @endif

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
