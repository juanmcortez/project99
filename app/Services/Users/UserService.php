<?php

namespace App\Services\Users;

use App\Enums\ActivityLogAction;
use App\Models\Users\User;
use App\Services\ActivityLogs\ActivityLogService;
use App\Services\Demographics\DemographicService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param  array{username: string, email: string, password: string}  $data
     */
    public static function create(array $data): User
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param  array{username?: string, email?: string}  $data
     */
    public static function updateProfile(User $user, array $data): User
    {
        $attributes = [];

        if (array_key_exists('username', $data)) {
            $attributes['username'] = $data['username'];
        }

        if (array_key_exists('email', $data)) {
            $attributes['email'] = $data['email'];
        }

        $emailChanging = array_key_exists('email', $attributes)
            && $attributes['email'] !== $user->email
            && $user instanceof MustVerifyEmail;

        if ($emailChanging) {
            $attributes['email_verified_at'] = null;
        }

        $user->forceFill($attributes)->save();

        if ($emailChanging) {
            $user->sendEmailVerificationNotification();
        }

        ActivityLogService::log(
            ActivityLogAction::UserProfileUpdated,
            'User profile updated',
            ['user_id' => $user->getKey()]
        );

        return $user->fresh();
    }

    public static function delete(User $user): void
    {
        if ($user->demographic !== null) {
            DemographicService::delete($user->demographic);
        }

        ActivityLogService::log(
            ActivityLogAction::UserAccountDeleted,
            'User account deleted',
            ['user_id' => $user->getKey()],
            $user->getKey()
        );

        $user->delete();
    }

    public static function updateRole(User $user, string $role): User
    {
        $user->syncRoles([$role]);

        ActivityLogService::log(
            ActivityLogAction::UserRoleUpdated,
            'User role updated',
            [
                'user_id' => $user->getKey(),
                'role' => $role,
            ]
        );

        return $user->fresh();
    }
}
