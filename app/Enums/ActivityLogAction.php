<?php

namespace App\Enums;

enum ActivityLogAction: string
{
    case UserLogin = 'user_login';
    case UserLogout = 'user_logout';
    case UserRegistered = 'user_registered';
    case UserProfileUpdated = 'user_profile_updated';
    case UserAccountDeleted = 'user_account_deleted';
    case DemographicCreated = 'demographic_created';
    case DemographicUpdated = 'demographic_updated';
    case ProfilePictureUpdated = 'profile_picture_updated';
    case AddressCreated = 'address_created';
    case AddressUpdated = 'address_updated';
    case PhoneCreated = 'phone_created';
    case PhoneUpdated = 'phone_updated';
    case PhoneDeleted = 'phone_deleted';

    public function label(): string
    {
        return match ($this) {
            self::UserLogin => 'User login',
            self::UserLogout => 'User logout',
            self::UserRegistered => 'User registered',
            self::UserProfileUpdated => 'Profile updated',
            self::UserAccountDeleted => 'Account deleted',
            self::DemographicCreated => 'Demographic created',
            self::DemographicUpdated => 'Demographic updated',
            self::ProfilePictureUpdated => 'Profile picture updated',
            self::AddressCreated => 'Address created',
            self::AddressUpdated => 'Address updated',
            self::PhoneCreated => 'Phone created',
            self::PhoneUpdated => 'Phone updated',
            self::PhoneDeleted => 'Phone deleted',
        };
    }
}
