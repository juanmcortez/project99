<div data-tab-panel="user-details" class="tab-panel hidden space-y-4">
    <h2 class="text-lg font-medium text-gray-900">User details</h2>

    @if (session('status') === 'demographic-saved')
        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            Demographic information saved successfully.
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

            <div>
                <x-input-label for="gender" value="Gender" />
                <x-select-input id="gender" name="gender" class="mt-1" :options="\App\Enums\Gender::cases()" :selected="old('gender', $demographic?->gender?->value)" />
                @error('gender', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="race" value="Race" />
                <x-select-input id="race" name="race" class="mt-1" :options="\App\Enums\Race::cases()" :selected="old('race', $demographic?->race?->value)" />
                @error('race', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="ethnicity" value="Ethnicity" />
                <x-select-input id="ethnicity" name="ethnicity" class="mt-1" :options="\App\Enums\Ethnicity::cases()" :selected="old('ethnicity', $demographic?->ethnicity?->value)" />
                @error('ethnicity', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="language" value="Language" />
                <x-select-input id="language" name="language" class="mt-1" :options="\App\Enums\Language::cases()" :selected="old('language', $demographic?->language?->value)" />
                @error('language', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="marital_status" value="Marital status" />
                <x-select-input id="marital_status" name="marital_status" class="mt-1" :options="\App\Enums\MaritalStatus::cases()" :selected="old('marital_status', $demographic?->marital_status?->value)" />
                @error('marital_status', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="education_level" value="Education level" />
                <x-select-input id="education_level" name="education_level" class="mt-1" :options="\App\Enums\EducationLevel::cases()" :selected="old('education_level', $demographic?->education_level?->value)" />
                @error('education_level', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="employment_status" value="Employment status" />
                <x-select-input id="employment_status" name="employment_status" class="mt-1" :options="\App\Enums\EmploymentStatus::cases()" :selected="old('employment_status', $demographic?->employment_status?->value)" />
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

            <div>
                <x-input-label for="income" value="Income" />
                <x-text-input id="income" name="income" type="number" step="0.01" min="0" class="mt-1" :value="old('income', $demographic?->income)" />
                @error('income', 'updateDemographic')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Save user details
        </button>
    </form>
</div>
