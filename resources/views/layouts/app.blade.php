<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/project99.css', 'resources/js/project99.js'])
        @stack('styles')
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900">
                    {{ config('app.name') }}
                </a>

                <div class="flex items-center gap-4 text-sm">
                    <a
                        href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'font-medium text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}"
                    >
                        Dashboard
                    </a>

                    <x-nav-dropdown :user="auth()->user()" />
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
            @yield('content')
        </main>
        @stack('scripts')
    </body>
</html>
