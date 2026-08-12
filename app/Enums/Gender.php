<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case NonBinary = 'non_binary';
    case Genderqueer = 'genderqueer';
    case Agender = 'agender';
    case PreferNotToSay = 'prefer_not_to_say';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::NonBinary => 'Non-binary',
            self::Genderqueer => 'Genderqueer',
            self::Agender => 'Agender',
            self::PreferNotToSay => 'Prefer not to say',
            self::Other => 'Other',
        };
    }
}
