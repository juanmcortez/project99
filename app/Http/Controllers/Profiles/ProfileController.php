<?php

namespace App\Http\Controllers\Profiles;

use App\Exceptions\AddressAlreadyExistsException;
use App\Exceptions\DemographicAlreadyExistsException;
use App\Exceptions\PhoneLimitReachedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\StoreAddressRequest;
use App\Http\Requests\Addresses\UpdateAddressRequest;
use App\Http\Requests\Demographics\StoreDemographicRequest;
use App\Http\Requests\Demographics\UpdateDemographicRequest;
use App\Http\Requests\Phones\StorePhoneRequest;
use App\Http\Requests\Phones\UpdatePhoneRequest;
use App\Http\Requests\Users\DeleteAccountRequest;
use App\Models\Phones\Phone;
use App\Models\Users\User;
use App\Services\Addresses\AddressService;
use App\Services\Demographics\DemographicService;
use App\Services\Phones\PhoneService;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();
        $user->load('demographic.address', 'demographic.phones');

        return view('profile.edit', [
            'user' => $user,
            'demographic' => $user->demographic,
            'address' => $user->demographic?->address,
            'phones' => $user->demographic?->phones ?? collect(),
        ]);
    }

    public function storeDemographic(StoreDemographicRequest $request): RedirectResponse
    {
        try {
            DemographicService::createFor($request->user(), $request->validated());
        } catch (DemographicAlreadyExistsException) {
            return redirect()
                ->to(route('profile.edit').'#user-details')
                ->withErrors(['demographic' => 'Demographic information already exists.'], 'updateDemographic');
        }

        return redirect()
            ->to(route('profile.edit').'#user-details')
            ->with('status', 'demographic-saved');
    }

    public function updateDemographic(UpdateDemographicRequest $request): RedirectResponse
    {
        $demographic = $request->user()->demographic;

        if ($demographic === null) {
            return redirect()
                ->to(route('profile.edit').'#user-details')
                ->withErrors(['demographic' => 'No demographic record found to update.'], 'updateDemographic');
        }

        DemographicService::update($demographic, $request->validated());

        return redirect()
            ->to(route('profile.edit').'#user-details')
            ->with('status', 'demographic-saved');
    }

    public function storeAddress(StoreAddressRequest $request): RedirectResponse
    {
        $demographic = $request->user()->demographic;

        if ($demographic === null) {
            return redirect()
                ->to(route('profile.edit').'#user-details')
                ->withErrors(['address' => 'Save demographic information before adding an address.'], 'updateDemographic');
        }

        try {
            AddressService::createFor($demographic, $request->validated());
        } catch (AddressAlreadyExistsException) {
            return redirect()
                ->to(route('profile.edit').'#user-location')
                ->withErrors(['address' => 'Address information already exists.'], 'updateDemographic');
        }

        return redirect()
            ->to(route('profile.edit').'#user-location')
            ->with('status', 'address-saved');
    }

    public function updateAddress(UpdateAddressRequest $request): RedirectResponse
    {
        $address = $request->user()->demographic?->address;

        if ($address === null) {
            return redirect()
                ->to(route('profile.edit').'#user-location')
                ->withErrors(['address' => 'No address record found to update.'], 'updateDemographic');
        }

        AddressService::update($address, $request->validated());

        return redirect()
            ->to(route('profile.edit').'#user-location')
            ->with('status', 'address-saved');
    }

    public function storePhone(StorePhoneRequest $request): RedirectResponse
    {
        $demographic = $request->user()->demographic;

        if ($demographic === null) {
            return redirect()
                ->to(route('profile.edit').'#user-details')
                ->withErrors(['phone' => 'Save demographic information before adding a phone number.'], 'updateDemographic');
        }

        try {
            PhoneService::createFor($demographic, $request->validated());
        } catch (PhoneLimitReachedException) {
            return redirect()
                ->to(route('profile.edit').'#user-contact')
                ->withErrors(['phone' => 'You can only add up to two phone numbers.'], 'updateDemographic');
        }

        return redirect()
            ->to(route('profile.edit').'#user-contact')
            ->with('status', 'phone-saved');
    }

    public function updatePhone(UpdatePhoneRequest $request, Phone $phone): RedirectResponse
    {
        if (! $this->phoneBelongsToUser($phone, $request->user())) {
            abort(403);
        }

        PhoneService::update($phone, $request->validated());

        return redirect()
            ->to(route('profile.edit').'#user-contact')
            ->with('status', 'phone-saved');
    }

    public function destroyPhone(Phone $phone): RedirectResponse
    {
        if (! $this->phoneBelongsToUser($phone, auth()->user())) {
            abort(403);
        }

        PhoneService::delete($phone);

        return redirect()
            ->to(route('profile.edit').'#user-contact')
            ->with('status', 'phone-deleted');
    }

    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $user = $request->user();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        UserService::delete($user);

        return redirect()->route('login');
    }

    private function phoneBelongsToUser(Phone $phone, User $user): bool
    {
        $demographic = $phone->demographic;

        return $demographic !== null
            && $demographic->demographicable_type === $user->getMorphClass()
            && $demographic->demographicable_id === $user->id;
    }
}
