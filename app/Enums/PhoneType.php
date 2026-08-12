<?php

namespace App\Enums;

enum PhoneType: string
{
    case Mobile = 'mobile';
    case Home = 'home';
    case Work = 'work';
    case Fax = 'fax';
    case Emergency = 'emergency';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Mobile => 'Mobile',
            self::Home => 'Home',
            self::Work => 'Work',
            self::Fax => 'Fax',
            self::Emergency => 'Emergency',
            self::Other => 'Other',
        };
    }
}
