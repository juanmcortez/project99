<?php

namespace App\Enums;

enum Race: string
{
    case AmericanIndianAlaskaNative = 'american_indian_alaska_native';
    case Asian = 'asian';
    case BlackAfricanAmerican = 'black_african_american';
    case NativeHawaiianPacificIslander = 'native_hawaiian_pacific_islander';
    case White = 'white';
    case TwoOrMoreRaces = 'two_or_more_races';
    case PreferNotToSay = 'prefer_not_to_say';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::AmericanIndianAlaskaNative => 'American Indian or Alaska Native',
            self::Asian => 'Asian',
            self::BlackAfricanAmerican => 'Black or African American',
            self::NativeHawaiianPacificIslander => 'Native Hawaiian or Other Pacific Islander',
            self::White => 'White',
            self::TwoOrMoreRaces => 'Two or More Races',
            self::PreferNotToSay => 'Prefer not to say',
            self::Other => 'Other',
        };
    }
}
