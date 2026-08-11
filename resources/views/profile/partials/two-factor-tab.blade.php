<div data-tab-panel="two-factor" class="tab-panel hidden max-w-lg space-y-6">
    <h2 class="text-lg font-medium text-gray-900">Two-factor authentication</h2>

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
                <h3 class="text-sm font-medium text-gray-900">Recovery codes</h3>
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
