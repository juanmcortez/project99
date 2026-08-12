<div data-tab-panel="user" class="tab-panel hidden space-y-4">
    <h2 class="text-lg font-medium text-gray-900">User</h2>

    @if ($errors->updateProfileInformation->any())
        <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->updateProfileInformation->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('user-profile-information.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="profile_username" value="Username" />
                @can('edit.profile.username')
                <x-text-input id="profile_username" name="username" type="text" class="mt-1" :value="old('username', auth()->user()->username)" required autocomplete="username" />
                <x-input-error for="username" />
                @else
                <p class="pt-3 pl-3 text-gray-500">{{ auth()->user()->username }}</p>
                @endcan
            </div>

            <div>
                <x-input-label for="profile_email" value="Email" />
                @can('edit.profile.email')
                <x-text-input id="profile_email" name="email" type="email" class="mt-1" :value="old('email', auth()->user()->email)" required autocomplete="email" />
                <x-input-error for="email" />
                @else
                <p class="mt-1 text-sm text-gray-500">{{ auth()->user()->email }}</p>
                @endcan
            </div>
        </div>

        @canany(['edit.profile.username', 'edit.profile.email'])
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Save profile
        </button>
        @endcan
    </form>
</div>
