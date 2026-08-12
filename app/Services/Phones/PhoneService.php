<?php

namespace App\Services\Phones;

use App\Enums\ActivityLogAction;
use App\Exceptions\PhoneLimitReachedException;
use App\Models\Demographics\Demographic;
use App\Models\Phones\Phone;
use App\Services\ActivityLogs\ActivityLogService;

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

            $phone = $trashed->fresh();

            ActivityLogService::log(
                ActivityLogAction::PhoneCreated,
                'Phone created',
                ['phone_id' => $phone->getKey()]
            );

            return $phone;
        }

        if ($demographic->phones()->count() >= 2) {
            throw new PhoneLimitReachedException;
        }

        $phone = $demographic->phones()->create($data);

        ActivityLogService::log(
            ActivityLogAction::PhoneCreated,
            'Phone created',
            ['phone_id' => $phone->getKey()]
        );

        return $phone;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(Phone $phone, array $data): Phone
    {
        $phone->update($data);

        ActivityLogService::log(
            ActivityLogAction::PhoneUpdated,
            'Phone updated',
            ['phone_id' => $phone->getKey()]
        );

        return $phone->fresh();
    }

    public static function delete(Phone $phone): void
    {
        ActivityLogService::log(
            ActivityLogAction::PhoneDeleted,
            'Phone deleted',
            ['phone_id' => $phone->getKey()]
        );

        $phone->delete();
    }
}
