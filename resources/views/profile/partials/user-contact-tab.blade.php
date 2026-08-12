<div data-tab-panel="user-contact" class="tab-panel hidden space-y-4">
    <h2 class="text-lg font-medium text-gray-900">User contact</h2>
    <p class="text-sm text-gray-600">You can add up to two phone numbers.</p>

    @if (session('status') === 'phone-saved')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Phone number saved successfully.
        </div>
    @endif

    @if (session('status') === 'phone-deleted')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Phone number deleted successfully.
        </div>
    @endif

    @if ($errors->updateDemographic->any())
        <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->updateDemographic->first() }}
        </div>
    @endif

    @if ($demographic === null)
        <p class="text-sm text-gray-500">Save user details first before adding phone numbers.</p>
    @else
        @foreach ($phones as $phone)
            <form
                method="POST"
                action="{{ route('profile.phone.update', $phone) }}"
                class="space-y-4 rounded-md border border-gray-200 p-4"
            >
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="phone_type_{{ $phone->id }}" value="Type" />
                        <x-text-input id="phone_type_{{ $phone->id }}" name="type" type="text" class="mt-1" :value="old('type', $phone->type)" required />
                        @error('type', 'updateDemographic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="phone_number_{{ $phone->id }}" value="Phone number (E.164)" />
                        <x-text-input id="phone_number_{{ $phone->id }}" name="phone_number" type="text" class="mt-1" :value="old('phone_number', $phone->phone_number)" placeholder="+12125551234" required />
                        @error('phone_number', 'updateDemographic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Save phone
                    </button>
                </div>
            </form>

            <form
                method="POST"
                action="{{ route('profile.phone.destroy', $phone) }}"
                class="mt-2"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                    Delete phone
                </button>
            </form>
        @endforeach

        @if ($phones->count() < 2)
            <form
                method="POST"
                action="{{ route('profile.phone.store') }}"
                class="space-y-4 rounded-md border border-dashed border-gray-300 p-4"
            >
                @csrf

                <p class="text-sm font-medium text-gray-700">Add phone number</p>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="new_phone_type" value="Type" />
                        <x-text-input id="new_phone_type" name="type" type="text" class="mt-1" :value="old('type')" placeholder="mobile" required />
                        @error('type', 'updateDemographic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="new_phone_number" value="Phone number (E.164)" />
                        <x-text-input id="new_phone_number" name="phone_number" type="text" class="mt-1" :value="old('phone_number')" placeholder="+12125551234" required />
                        @error('phone_number', 'updateDemographic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Add phone
                </button>
            </form>
        @endif
    @endif
</div>
