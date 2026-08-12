<?php

namespace App\Enums;

enum Ethnicity: string
{
    case HispanicLatino = 'hispanic_latino';
    case NotHispanicLatino = 'not_hispanic_latino';
    case PreferNotToSay = 'prefer_not_to_say';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HispanicLatino => 'Hispanic or Latino',
            self::NotHispanicLatino => 'Not Hispanic or Latino',
            self::PreferNotToSay => 'Prefer not to say',
            self::Other => 'Other',
        };
    }
}
