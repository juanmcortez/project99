<?php

namespace App\Services\Users;

use App\Models\Users\User;
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
     * @param  array{username: string, email: string}  $data
     */
    public static function updateProfile(User $user, array $data): User
    {
        if ($data['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $user->forceFill([
                'username' => $data['username'],
                'email' => $data['email'],
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill([
                'username' => $data['username'],
                'email' => $data['email'],
            ])->save();
        }

        return $user->fresh();
    }

    public static function delete(User $user): void
    {
        if ($user->demographic !== null) {
            DemographicService::delete($user->demographic);
        }

        $user->delete();
    }
}
