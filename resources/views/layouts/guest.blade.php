<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/project99.css', 'resources/js/project99.js'])
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </body>
</html>
