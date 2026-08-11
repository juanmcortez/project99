<?php

namespace App\Services\Phones;

use App\Exceptions\PhoneLimitReachedException;
use App\Models\Demographics\Demographic;
use App\Models\Phones\Phone;

class PhoneService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function createFor(Demographic $demographic, array $data): Phone
    {
        $trashed = $demographic->phones()
            ->withTrashed()
            ->where('type', $data['type'])
            ->first();

        if ($trashed !== null) {
            $trashed->restore();
            $trashed->update(['phone_number' => $data['phone_number']]);

            return $trashed->fresh();
        }

        if ($demographic->phones()->count() >= 2) {
            throw new PhoneLimitReachedException;
        }

        return $demographic->phones()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(Phone $phone, array $data): Phone
    {
        $phone->update($data);

        return $phone->fresh();
    }

    public static function delete(Phone $phone): void
    {
        $phone->delete();
    }
}
