@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Confirm password</h1>
        <p class="mt-2 text-sm text-gray-600">Please confirm your password before continuing.</p>

        <form method="POST" action="{{ route('password.confirm.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" name="password" type="password" class="mt-1" required autocomplete="current-password" />
                <x-input-error for="password" />
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Confirm
            </button>
        </form>
    </div>
@endsection
