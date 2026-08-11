<div data-tab-panel="demographics" class="tab-panel hidden max-w-lg space-y-4">
    <h2 class="text-lg font-medium text-gray-900">Demographic information</h2>

    @if (session('status') === 'demographic-saved')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Demographic information saved successfully.
        </div>
    @endif

    @if (session('status') === 'address-saved')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Address saved successfully.
        </div>
    @endif

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

    @if ($demographic?->profile_picture)
        <div>
            <p class="text-sm font-medium text-gray-700">Current profile picture</p>
            <img
                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($demographic->profile_picture) }}"
                alt="Profile picture"
                class="mt-2 h-24 w-24 rounded-full object-cover"
            />
        </div>
    @endif

    <form
        method="POST"
        action="{{ $demographic ? route('profile.demographic.update') : route('profile.demographic.store') }}"
        enctype="multipart/form-data"
        class="space-y-4"
    >
        @csrf
        @if ($demographic)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="first_name" value="First name" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1" :value="old('first_name', $demographic?->first_name)" required />
                @error('first_name', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="middle_name" value="Middle name" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1" :value="old('middle_name', $demographic?->middle_name)" />
                @error('middle_name', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <x-input-label for="last_name" value="Last name" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1" :value="old('last_name', $demographic?->last_name)" required />
            @error('last_name', 'updateDemographic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input-label for="birthdate" value="Birthdate" />
            <x-text-input
                id="birthdate"
                name="birthdate"
                type="date"
                class="mt-1"
                :value="old('birthdate', $demographic?->birthdate?->format('Y-m-d'))"
                required
            />
            @error('birthdate', 'updateDemographic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input-label for="profile_picture" value="Profile picture" />
            <input
                id="profile_picture"
                name="profile_picture"
                type="file"
                accept="image/*"
                class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
            />
            @error('profile_picture', 'updateDemographic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input-label for="social_security" value="Social security number" />
            <x-text-input
                id="social_security"
                name="social_security"
                type="password"
                class="mt-1"
                autocomplete="off"
                placeholder="{{ $demographic?->social_security ? 'Leave blank to keep current value' : '' }}"
            />
            @error('social_security', 'updateDemographic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="gender" value="Gender" />
                <x-text-input id="gender" name="gender" type="text" class="mt-1" :value="old('gender', $demographic?->gender)" />
                @error('gender', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="race" value="Race" />
                <x-text-input id="race" name="race" type="text" class="mt-1" :value="old('race', $demographic?->race)" />
                @error('race', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="ethnicity" value="Ethnicity" />
                <x-text-input id="ethnicity" name="ethnicity" type="text" class="mt-1" :value="old('ethnicity', $demographic?->ethnicity)" />
                @error('ethnicity', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="language" value="Language" />
                <x-text-input id="language" name="language" type="text" class="mt-1" :value="old('language', $demographic?->language)" />
                @error('language', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="marital_status" value="Marital status" />
                <x-text-input id="marital_status" name="marital_status" type="text" class="mt-1" :value="old('marital_status', $demographic?->marital_status)" />
                @error('marital_status', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="education_level" value="Education level" />
                <x-text-input id="education_level" name="education_level" type="text" class="mt-1" :value="old('education_level', $demographic?->education_level)" />
                @error('education_level', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="employment_status" value="Employment status" />
                <x-text-input id="employment_status" name="employment_status" type="text" class="mt-1" :value="old('employment_status', $demographic?->employment_status)" />
                @error('employment_status', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="occupation" value="Occupation" />
                <x-text-input id="occupation" name="occupation" type="text" class="mt-1" :value="old('occupation', $demographic?->occupation)" />
                @error('occupation', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <x-input-label for="income" value="Income" />
            <x-text-input id="income" name="income" type="number" step="0.01" min="0" class="mt-1" :value="old('income', $demographic?->income)" />
            @error('income', 'updateDemographic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Save demographics
        </button>
    </form>

    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-medium text-gray-900">Address</h3>
        <p class="mt-1 text-sm text-gray-600">Optional. Save demographic information first if you have not already.</p>

        @if ($demographic === null)
            <p class="mt-4 text-sm text-gray-500">Add demographic information above before saving an address.</p>
        @else
            <form
                method="POST"
                action="{{ $address ? route('profile.address.update') : route('profile.address.store') }}"
                class="mt-4 space-y-4"
            >
                @csrf
                @if ($address)
                    @method('PUT')
                @endif

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

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="zip_code" value="ZIP code" />
                        <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1" :value="old('zip_code', $address?->zip_code)" required />
                        @error('zip_code', 'updateDemographic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="country" value="Country (ISO code)" />
                        <x-text-input id="country" name="country" type="text" maxlength="2" class="mt-1 uppercase" :value="old('country', $address?->country)" placeholder="US" required />
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

    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-medium text-gray-900">Phone numbers</h3>
        <p class="mt-1 text-sm text-gray-600">Optional. You can add up to two phone numbers. Save demographic information first if you have not already.</p>

        @if ($demographic === null)
            <p class="mt-4 text-sm text-gray-500">Add demographic information above before saving phone numbers.</p>
        @else
            @foreach ($phones as $phone)
                <form
                    method="POST"
                    action="{{ route('profile.phone.update', $phone) }}"
                    class="mt-4 space-y-4 rounded-md border border-gray-200 p-4"
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
                    class="mt-4 space-y-4 rounded-md border border-dashed border-gray-300 p-4"
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
</div>
