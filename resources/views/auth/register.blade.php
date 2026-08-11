@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Create account</h1>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" name="username" type="text" class="mt-1" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error for="username" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email')" required autocomplete="email" />
                <x-input-error for="email" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" name="password" type="password" class="mt-1" required autocomplete="new-password" />
                <x-input-error for="password" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirm password" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1" required autocomplete="new-password" />
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Register
            </button>

            <p class="text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">Log in</a>
            </p>
        </form>
    </div>
@endsection
