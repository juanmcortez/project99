<div data-tab-panel="profile" class="tab-panel hidden max-w-lg space-y-4">
    <h2 class="text-lg font-medium text-gray-900">Profile information</h2>

    @if ($errors->updateProfileInformation->any())
        <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->updateProfileInformation->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('user-profile-information.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="profile_username" value="Username" />
            <x-text-input id="profile_username" name="username" type="text" class="mt-1" :value="old('username', auth()->user()->username)" required autocomplete="username" />
            <x-input-error for="username" />
        </div>

        <div>
            <x-input-label for="profile_email" value="Email" />
            <x-text-input id="profile_email" name="email" type="email" class="mt-1" :value="old('email', auth()->user()->email)" required autocomplete="email" />
            <x-input-error for="email" />
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Save profile
        </button>
    </form>
</div>
