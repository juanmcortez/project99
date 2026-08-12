<?php

namespace App\Enums;

enum EmploymentStatus: string
{
    case Employed = 'employed';
    case PartTimeEmployed = 'part_time_employed';
    case Unemployed = 'unemployed';
    case SelfEmployed = 'self_employed';
    case Student = 'student';
    case Retired = 'retired';
    case UnableToWork = 'unable_to_work';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Employed => 'Employed full-time',
            self::PartTimeEmployed => 'Employed part-time',
            self::Unemployed => 'Unemployed',
            self::SelfEmployed => 'Self-employed',
            self::Student => 'Student',
            self::Retired => 'Retired',
            self::UnableToWork => 'Unable to work',
            self::Other => 'Other',
        };
    }
}
