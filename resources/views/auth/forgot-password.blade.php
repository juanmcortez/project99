@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Forgot password</h1>
        <p class="mt-2 text-sm text-gray-600">Enter your email and we will send you a reset link.</p>

        <x-session-status class="mt-4" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email')" required autofocus autocomplete="email" />
                <x-input-error for="email" />
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Email reset link
            </button>

            <p class="text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">Back to login</a>
            </p>
        </form>
    </div>
@endsection
