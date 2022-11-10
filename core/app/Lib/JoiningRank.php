<?php

namespace App\Lib;

class JoiningRank
{
    public const PERL = 1;
    public const SAPPHIRE = 2;
    public const RUBY = 3;
    public const EMERALD = 4;
    public const MARKET_BUILDER = 5;
    public const ASSOCIATE_MARKET_MENTOR = 6;
    public const MARKET_MENTOR = 7;
    public const GOLD_DIRECTOR = 8;
    public const MANAGING_MARKET_MENTOR = 9;
    public const VICE_PRESIDENT = 10;
    public const SENIOR_VICE_PRESIDENT = 11;
    public const GOLD_PRESIDENT = 12;

    public static function getRankName($rank)
    {
        switch ($rank) {
            case 1:
                return 'PERL';
            case 2:
                return 'SAPPHIRE';
            case 3:
                return 'RUBY';
            case 4:
                return 'EMERALD';
            case 5:
                return 'MARKET BUILDER';
            case 6:
                return 'ASSOCIATE MARKET MENTOR';
            case 7:
                return 'MARKET MENTOR';
            case 8:
                return 'GOLD DIRECTOR';
            case 9:
                return 'MANAGING MARKET MENTOR';
            case 10:
                return 'VICE PRESIDENT';
            case 11:
                return 'SENIOR VICE PRESIDENT';
            case 12:
                return 'GOLD PRESIDENT';
            default:
                return 'N/A';
        }
    }

    public static function getRankAccountMap()
    {
        return [
            self::PERL => 10,
            self::SAPPHIRE => 60,
            self::RUBY => 160,
            self::EMERALD => 360,
            self::MARKET_BUILDER => 760,
            self::ASSOCIATE_MARKET_MENTOR => 1560,
            self::MARKET_MENTOR => 1600,
            self::GOLD_DIRECTOR => 3160,
            self::MANAGING_MARKET_MENTOR => 9560,
            self::VICE_PRESIDENT => 22360,
            self::SENIOR_VICE_PRESIDENT => 47960,
            self::GOLD_PRESIDENT => 99160,
        ];
    }

    public static function getUpgradeBonus($rank)
    {
        return [
            self::PERL => 10,
            self::SAPPHIRE => 50,
            self::RUBY => 100,
            self::EMERALD => 200,
            self::MARKET_BUILDER => 300,
            self::ASSOCIATE_MARKET_MENTOR => 500,
            self::MARKET_MENTOR => 900,
            self::GOLD_DIRECTOR => 1600,
            self::MANAGING_MARKET_MENTOR => 3000,
            self::VICE_PRESIDENT => 6000,
            self::SENIOR_VICE_PRESIDENT => 12340,
            self::GOLD_PRESIDENT => 25000,
        ][$rank] ?? 0;
    }
}
