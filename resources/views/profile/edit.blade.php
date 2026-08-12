@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your account settings.</p>
        </div>

        <div class="border-b border-gray-200 px-6">
            <nav class="-mb-px flex gap-6" aria-label="Profile tabs">
                <a href="#user" data-tab-target="user" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    User
                </a>
                <a href="#user-details" data-tab-target="user-details" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    User details
                </a>
                <a href="#user-location" data-tab-target="user-location" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    User location
                </a>
                <a href="#user-contact" data-tab-target="user-contact" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    User contact
                </a>
                <a href="#security" data-tab-target="security" class="tab-link border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Security
                </a>
            </nav>
        </div>

        <div class="p-6">
            @include('profile.partials.user-tab')
            @include('profile.partials.user-details-tab')
            @include('profile.partials.user-location-tab')
            @include('profile.partials.user-contact-tab')
            @include('profile.partials.security-tab')
        </div>
    </div>
@endsection
