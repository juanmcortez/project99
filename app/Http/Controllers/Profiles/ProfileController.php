<?php

namespace App\Http\Controllers\Profiles;

use App\Exceptions\AddressAlreadyExistsException;
use App\Exceptions\DemographicAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\StoreAddressRequest;
use App\Http\Requests\Addresses\UpdateAddressRequest;
use App\Http\Requests\Demographics\StoreDemographicRequest;
use App\Http\Requests\Demographics\UpdateDemographicRequest;
use App\Services\Addresses\AddressService;
use App\Services\Demographics\DemographicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();
        $user->load('demographic.address');

        return view('profile.edit', [
            'user' => $user,
            'demographic' => $user->demographic,
            'address' => $user->demographic?->address,
        ]);
    }

    public function storeDemographic(StoreDemographicRequest $request): RedirectResponse
    {
        try {
            DemographicService::createFor($request->user(), $request->validated());
        } catch (DemographicAlreadyExistsException) {
            return redirect()
                ->to(route('profile.edit').'#demographics')
                ->withErrors(['demographic' => 'Demographic information already exists.'], 'updateDemographic');
        }

        return redirect()
            ->to(route('profile.edit').'#demographics')
            ->with('status', 'demographic-saved');
    }

    public function updateDemographic(UpdateDemographicRequest $request): RedirectResponse
    {
        $demographic = $request->user()->demographic;

        if ($demographic === null) {
            return redirect()
                ->to(route('profile.edit').'#demographics')
                ->withErrors(['demographic' => 'No demographic record found to update.'], 'updateDemographic');
        }

        DemographicService::update($demographic, $request->validated());

        return redirect()
            ->to(route('profile.edit').'#demographics')
            ->with('status', 'demographic-saved');
    }

    public function storeAddress(StoreAddressRequest $request): RedirectResponse
    {
        $demographic = $request->user()->demographic;

        if ($demographic === null) {
            return redirect()
                ->to(route('profile.edit').'#demographics')
                ->withErrors(['address' => 'Save demographic information before adding an address.'], 'updateDemographic');
        }

        try {
            AddressService::createFor($demographic, $request->validated());
        } catch (AddressAlreadyExistsException) {
            return redirect()
                ->to(route('profile.edit').'#demographics')
                ->withErrors(['address' => 'Address information already exists.'], 'updateDemographic');
        }

        return redirect()
            ->to(route('profile.edit').'#demographics')
            ->with('status', 'address-saved');
    }

    public function updateAddress(UpdateAddressRequest $request): RedirectResponse
    {
        $address = $request->user()->demographic?->address;

        if ($address === null) {
            return redirect()
                ->to(route('profile.edit').'#demographics')
                ->withErrors(['address' => 'No address record found to update.'], 'updateDemographic');
        }

        AddressService::update($address, $request->validated());

        return redirect()
            ->to(route('profile.edit').'#demographics')
            ->with('status', 'address-saved');
    }
}
