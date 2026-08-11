<div data-tab-panel="password" class="tab-panel hidden max-w-lg space-y-4">
    <h2 class="text-lg font-medium text-gray-900">Update password</h2>

    @if ($errors->updatePassword->any())
        <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->updatePassword->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="current_password" value="Current password" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1" required autocomplete="current-password" />
            <x-input-error for="current_password" />
        </div>

        <div>
            <x-input-label for="new_password" value="New password" />
            <x-text-input id="new_password" name="password" type="password" class="mt-1" required autocomplete="new-password" />
            <x-input-error for="password" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm new password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1" required autocomplete="new-password" />
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Update password
        </button>
    </form>
</div>
