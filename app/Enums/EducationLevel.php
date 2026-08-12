<?php

namespace App\Enums;

enum EducationLevel: string
{
    case LessThanHighSchool = 'less_than_high_school';
    case HighSchool = 'high_school';
    case SomeCollege = 'some_college';
    case AssociateDegree = 'associate_degree';
    case BachelorsDegree = 'bachelors_degree';
    case MastersDegree = 'masters_degree';
    case DoctoralDegree = 'doctoral_degree';
    case ProfessionalDegree = 'professional_degree';
    case TradeVocational = 'trade_vocational';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::LessThanHighSchool => 'Less than high school',
            self::HighSchool => 'High school diploma or equivalent',
            self::SomeCollege => 'Some college, no degree',
            self::AssociateDegree => 'Associate degree',
            self::BachelorsDegree => "Bachelor's degree",
            self::MastersDegree => "Master's degree",
            self::DoctoralDegree => 'Doctoral degree',
            self::ProfessionalDegree => 'Professional degree',
            self::TradeVocational => 'Trade or vocational school',
            self::Other => 'Other',
        };
    }
}
