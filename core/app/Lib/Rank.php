<?php

namespace App\Lib;

class Rank
{
    public const ASSOCIATE = 1;
    public const PROMOTER = 2;
    public const CONSULTANT = 3;
    public const DIRECTOR = 4;
    public const BRONZE_DIRECTOR = 5;
    public const RUBY_DIRECTOR = 6;
    public const EMERALD_DIRECTOR = 7;
    public const GOLD_DIRECTOR = 8;
    public const DIAMOND = 9;
    public const PLATINUM = 10;
    public const TITANIUM = 11;
    public const CROWN = 12;

    public static function getRankName($rank)
    {
        switch ($rank) {
            case 1:
                return 'ASSOCIATE';
            case 2:
                return 'PROMOTER';
            case 3:
                return 'CONSULTANT';
            case 4:
                return 'DIRECTOR';
            case 5:
                return 'BRONZE_DIRECTOR';
            case 6:
                return 'RUBY_DIRECTOR';
            case 7:
                return 'EMERALD_DIRECTOR';
            case 8:
                return 'GOLD_DIRECTOR';
            case 9:
                return 'DIAMOND';
            case 10:
                return 'PLATINUM';
            case 11:
                return 'TITANIUM';
            case 12:
                return 'CROWN';
            default:
                return 'N/A';
        }
    }

    public static function getRankAmountMap()
    {
        return [
            self::ASSOCIATE => 500,
            self::PROMOTER => 1250,
            self::CONSULTANT => 2500,
            self::DIRECTOR => 5000,
            self::BRONZE_DIRECTOR => 10000,
            self::RUBY_DIRECTOR => 15000,
            self::EMERALD_DIRECTOR => 20000,
            self::GOLD_DIRECTOR => 35000,
            self::DIAMOND => 50000,
            self::PLATINUM => 75000,
            self::TITANIUM => 150000,
            self::CROWN => 450000,
        ];
    }

    public static function getUpgradeBonus($rank)
    {
        return [
            self::ASSOCIATE => 35,
            self::PROMOTER => 50,
            self::CONSULTANT => 85,
            self::DIRECTOR => 135,
            self::BRONZE_DIRECTOR => 270,
            self::RUBY_DIRECTOR => 405,
            self::EMERALD_DIRECTOR => 700,
            self::GOLD_DIRECTOR => 1200,
            self::DIAMOND => 1500,
            self::PLATINUM => 2000,
            self::TITANIUM => 3500,
            self::CROWN => 13000,
        ][$rank] ?? 0;
    }
}
