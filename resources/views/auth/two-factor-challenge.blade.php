@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Two-factor authentication</h1>
        <p class="mt-2 text-sm text-gray-600">Enter the code from your authenticator app or a recovery code.</p>

        <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="code" value="Authentication code" />
                <x-text-input id="code" name="code" type="text" class="mt-1" inputmode="numeric" autofocus autocomplete="one-time-code" />
                <x-input-error for="code" />
            </div>

            <div>
                <x-input-label for="recovery_code" value="Recovery code" />
                <x-text-input id="recovery_code" name="recovery_code" type="text" class="mt-1" autocomplete="one-time-code" />
                <x-input-error for="recovery_code" />
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Continue
            </button>
        </form>
    </div>
@endsection
