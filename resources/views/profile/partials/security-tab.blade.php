<div data-tab-panel="security" class="tab-panel hidden space-y-6">
    <h2 class="text-lg font-medium text-gray-900">Security</h2>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="space-y-4">
            <h3 class="text-base font-medium text-gray-900">Update password</h3>

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

        <div class="space-y-4">
            <h3 class="text-base font-medium text-gray-900">Two-factor authentication</h3>

            @if (session('status') === 'two-factor-authentication-enabled')
                <div class="rounded-md bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    Two-factor authentication is enabled. Scan the QR code and confirm with a code from your authenticator app.
                </div>
            @endif

            @if (session('status') === 'two-factor-authentication-confirmed')
                <div class="rounded-md bg-green-50 px-4 py-3 text-sm text-green-800">
                    Two-factor authentication confirmed and enabled successfully.
                </div>
            @endif

            @if (! auth()->user()->two_factor_secret)
                <p class="text-sm text-gray-600">
                    Add an extra layer of security to your account using an authenticator app.
                </p>

                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Enable two-factor authentication
                    </button>
                </form>
            @else
                @if (! auth()->user()->two_factor_confirmed_at)
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Scan this QR code with your authenticator app.</p>
                        <div class="rounded-md border border-gray-200 bg-white p-4">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="code" value="Authentication code" />
                                <x-text-input id="code" name="code" type="text" class="mt-1" inputmode="numeric" required autocomplete="one-time-code" />
                                <x-input-error for="code" />
                            </div>
                            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                Confirm two-factor authentication
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-gray-600">Two-factor authentication is enabled on your account.</p>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-gray-900">Recovery codes</h4>
                        <ul class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-mono text-gray-800">
                            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                <li>{{ $code }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Regenerate recovery codes
                        </button>
                    </form>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                            Disable two-factor authentication
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="border-t border-red-200 pt-6">
        <h3 class="text-base font-medium text-red-900">Danger zone</h3>
        <p class="mt-2 text-sm text-gray-600">
            Permanently delete your account and all associated data. This action cannot be undone.
        </p>

        @if ($errors->deleteAccount->any())
            <div class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->deleteAccount->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <x-input-label for="delete_password" value="Confirm your password" />
                <x-text-input id="delete_password" name="password" type="password" class="mt-1" required autocomplete="current-password" />
                @error('password', 'deleteAccount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                Delete my account
            </button>
        </form>
    </div>
</div>
