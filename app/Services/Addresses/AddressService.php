<?php

namespace App\Services\Addresses;

use App\Exceptions\AddressAlreadyExistsException;
use App\Models\Addresses\Address;
use App\Models\Demographics\Demographic;

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

        return $demographic->address()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(Address $address, array $data): Address
    {
        $address->update($data);

        return $address->fresh();
    }
}
