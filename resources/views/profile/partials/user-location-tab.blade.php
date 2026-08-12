<div data-tab-panel="user-location" class="tab-panel hidden space-y-4">
    <h2 class="text-lg font-medium text-gray-900">User location</h2>

    @if (session('status') === 'address-saved')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Address saved successfully.
        </div>
    @endif

    @if ($errors->updateDemographic->any())
        <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->updateDemographic->first() }}
        </div>
    @endif

    @if ($demographic === null)
        <p class="text-sm text-gray-500">Save user details first before adding an address.</p>
    @else
        <form
            method="POST"
            action="{{ $address ? route('profile.address.update') : route('profile.address.store') }}"
            class="space-y-4"
        >
            @csrf
            @if ($address)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="street_line_1" value="Street line 1" />
                    <x-text-input id="street_line_1" name="street_line_1" type="text" class="mt-1" :value="old('street_line_1', $address?->street_line_1)" required />
                    @error('street_line_1', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="street_line_2" value="Street line 2" />
                    <x-text-input id="street_line_2" name="street_line_2" type="text" class="mt-1" :value="old('street_line_2', $address?->street_line_2)" />
                    @error('street_line_2', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="city" value="City" />
                    <x-text-input id="city" name="city" type="text" class="mt-1" :value="old('city', $address?->city)" required />
                    @error('city', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="state" value="State" />
                    <x-text-input id="state" name="state" type="text" class="mt-1" :value="old('state', $address?->state)" required />
                    @error('state', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="zip_code" value="ZIP code" />
                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1" :value="old('zip_code', $address?->zip_code)" required />
                    @error('zip_code', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="country" value="Country" />
                    <x-select-input id="country" name="country" class="mt-1" :options="\App\Enums\Country::cases()" :selected="old('country', $address?->country?->value)" required />
                    @error('country', 'updateDemographic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Save address
            </button>
        </form>
    @endif
</div>
