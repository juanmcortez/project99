@extends('layouts.guest')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">Verify your email</h1>
        <p class="mt-2 text-sm text-gray-600">
            Thanks for signing up. Please verify your email address by clicking the link we sent you.
        </p>

        <x-session-status class="mt-4" />

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Resend verification email
            </button>
        </form>
    </div>
@endsection
