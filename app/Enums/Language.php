<?php

namespace App\Enums;

enum Language: string
{
    case English = 'english';
    case Spanish = 'spanish';
    case French = 'french';
    case German = 'german';
    case MandarinChinese = 'mandarin_chinese';
    case Portuguese = 'portuguese';
    case Arabic = 'arabic';
    case Russian = 'russian';
    case Japanese = 'japanese';
    case Korean = 'korean';
    case Hindi = 'hindi';
    case Bengali = 'bengali';
    case Italian = 'italian';
    case Dutch = 'dutch';
    case Polish = 'polish';
    case Turkish = 'turkish';
    case Vietnamese = 'vietnamese';
    case Thai = 'thai';
    case Greek = 'greek';
    case Hebrew = 'hebrew';
    case Swedish = 'swedish';
    case Norwegian = 'norwegian';
    case Danish = 'danish';
    case Finnish = 'finnish';
    case Urdu = 'urdu';
    case Tagalog = 'tagalog';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Spanish => 'Spanish',
            self::French => 'French',
            self::German => 'German',
            self::MandarinChinese => 'Mandarin Chinese',
            self::Portuguese => 'Portuguese',
            self::Arabic => 'Arabic',
            self::Russian => 'Russian',
            self::Japanese => 'Japanese',
            self::Korean => 'Korean',
            self::Hindi => 'Hindi',
            self::Bengali => 'Bengali',
            self::Italian => 'Italian',
            self::Dutch => 'Dutch',
            self::Polish => 'Polish',
            self::Turkish => 'Turkish',
            self::Vietnamese => 'Vietnamese',
            self::Thai => 'Thai',
            self::Greek => 'Greek',
            self::Hebrew => 'Hebrew',
            self::Swedish => 'Swedish',
            self::Norwegian => 'Norwegian',
            self::Danish => 'Danish',
            self::Finnish => 'Finnish',
            self::Urdu => 'Urdu',
            self::Tagalog => 'Tagalog',
            self::Other => 'Other',
        };
    }
}
