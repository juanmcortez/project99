@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your account settings.</p>
        </div>

        <div class="border-b border-gray-200 px-6">
            <nav class="-mb-px flex gap-6" aria-label="Profile tabs">
                <a href="#profile" data-tab-target="profile" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Profile
                </a>
                <a href="#password" data-tab-target="password" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Password
                </a>
                <a href="#two-factor" data-tab-target="two-factor" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Two-factor
                </a>
            </nav>
        </div>

        <div class="p-6">
            @include('profile.partials.profile-tab')
            @include('profile.partials.password-tab')
            @include('profile.partials.two-factor-tab')
        </div>
    </div>
@endsection
