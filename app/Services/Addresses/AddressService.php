<?php

namespace App\Services\Addresses;

use App\Enums\ActivityLogAction;
use App\Exceptions\AddressAlreadyExistsException;
use App\Models\Addresses\Address;
use App\Models\Demographics\Demographic;
use App\Services\ActivityLogs\ActivityLogService;

class AddressService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function createFor(Demographic $demographic, array $data): Address
    {
        if ($demographic->address()->exists()) {
            throw new AddressAlreadyExistsException;
        }

        $address = $demographic->address()->create($data);

        ActivityLogService::log(
            ActivityLogAction::AddressCreated,
            'Address created',
            ['address_id' => $address->getKey()]
        );

        return $address;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(Address $address, array $data): Address
    {
        $address->update($data);

        ActivityLogService::log(
            ActivityLogAction::AddressUpdated,
            'Address updated',
            ['address_id' => $address->getKey()]
        );

        return $address->fresh();
    }
}
