<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case Single = 'single';
    case Married = 'married';
    case Divorced = 'divorced';
    case Widowed = 'widowed';
    case Separated = 'separated';
    case DomesticPartnership = 'domestic_partnership';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Single => 'Single',
            self::Married => 'Married',
            self::Divorced => 'Divorced',
            self::Widowed => 'Widowed',
            self::Separated => 'Separated',
            self::DomesticPartnership => 'Domestic partnership',
            self::Other => 'Other',
        };
    }
}
