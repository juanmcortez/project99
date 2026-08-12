<?php

namespace App\Actions\Fortify;

use App\Http\Requests\Users\UpdateProfileInformationRequest;
use App\Models\Users\User;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        $profileRequest = app(UpdateProfileInformationRequest::class);

        if (! $profileRequest->authorize()) {
            abort(403);
        }

        $validated = Validator::make($input, $profileRequest->rules())
            ->validateWithBag('updateProfileInformation');

        UserService::updateProfile($user, $validated);
    }
}
