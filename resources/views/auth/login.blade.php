@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Log in</h1>
        <p class="mt-2 text-sm text-gray-600">Use your username or email address.</p>

        <x-session-status class="mt-4" />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="login" value="Username or email" />
                <x-text-input id="login" name="login" type="text" class="mt-1" :value="old('login')" required autofocus autocomplete="username" />
                <x-input-error for="login" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" name="password" type="password" class="mt-1" required autocomplete="current-password" />
                <x-input-error for="password" />
            </div>

            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                <label for="remember" class="ms-2 text-sm text-gray-600">Remember me</label>
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Log in
            </button>

            <div class="flex items-center justify-between text-sm">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800">Forgot password?</a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800">Create account</a>
                @endif
            </div>
        </form>
    </div>
@endsection
