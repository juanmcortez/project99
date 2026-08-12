<?php

namespace App\Services\Demographics;

use App\Enums\ActivityLogAction;
use App\Exceptions\DemographicAlreadyExistsException;
use App\Models\Demographics\Demographic;
use App\Models\Phones\Phone;
use App\Services\ActivityLogs\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DemographicService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function createFor(Model $parent, array $data): Demographic
    {
        if ($parent->demographic()->exists()) {
            throw new DemographicAlreadyExistsException;
        }

        $attributes = self::prepareAttributes($data);

        $demographic = $parent->demographic()->create($attributes);

        ActivityLogService::log(
            ActivityLogAction::DemographicCreated,
            'Demographic information created',
            ['demographic_id' => $demographic->getKey()]
        );

        return $demographic;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function update(Demographic $demographic, array $data): Demographic
    {
        $profilePictureUploaded = isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile;

        $attributes = self::prepareAttributes($data, $demographic);

        $demographic->update($attributes);

        ActivityLogService::log(
            ActivityLogAction::DemographicUpdated,
            'Demographic information updated',
            ['demographic_id' => $demographic->getKey()]
        );

        if ($profilePictureUploaded) {
            ActivityLogService::log(
                ActivityLogAction::ProfilePictureUpdated,
                'Profile picture updated',
                ['demographic_id' => $demographic->getKey()]
            );
        }

        return $demographic->fresh();
    }

    public static function delete(Demographic $demographic): void
    {
        $demographic->address?->delete();
        $demographic->phones()->each(fn (Phone $phone) => $phone->delete());
        $demographic->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function prepareAttributes(array $data, ?Demographic $existing = null): array
    {
        $attributes = collect($data)
            ->except('profile_picture')
            ->all();

        if (array_key_exists('social_security', $attributes) && blank($attributes['social_security'])) {
            unset($attributes['social_security']);
        }

        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            if ($existing?->profile_picture) {
                Storage::disk('public')->delete($existing->profile_picture);
            }

            $attributes['profile_picture'] = $data['profile_picture']->store('profile-pictures', 'public');
        }

        return $attributes;
    }
}
