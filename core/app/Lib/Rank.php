<?php

namespace App\Lib;

class Rank
{
    public const STAR = 1;
    public const SILVER_STAR = 2;
    public const PEARL_STAR = 3;
    public const GOLD_STAR = 4;
    public const EMERALD_STAR = 5;
    public const PLATINUM_STAR = 6;
    public const ROYAL_STAR = 7;
    public const DIAMOND = 8;
    public const DIAMOND_STAR = 9;
    public const ROYAL_DIAMOND = 10;
    public const CROWN = 11;
    public const CROWN_AMBASSADOR = 12;

    public static function getRankName($rank)
    {
        switch ($rank) {
            case 1:
                return 'STAR';
            case 2:
                return 'SILVER STAR';
            case 3:
                return 'PEARL STAR';
            case 4:
                return 'GOLD STAR';
            case 5:
                return 'EMERALD STAR';
            case 6:
                return 'PLATINUM STAR';
            case 7:
                return 'ROYAL STAR';
            case 8:
                return 'DIAMOND';
            case 9:
                return 'DIAMOND STAR';
            case 10:
                return 'ROYAL DIAMOND';
            case 11:
                return 'CROWN';
            case 12:
                return 'CROWN AMBASSADOR';
            default:
                return 'N/A';
        }
    }

    public static function getRankAmountMap()
    {
        return [
            self::STAR => 700,
            self::SILVER_STAR => 2100,
            self::PEARL_STAR => 6300,
            self::GOLD_STAR => 18900,
            self::EMERALD_STAR => 35000,
            self::PLATINUM_STAR => 75000,
            self::ROYAL_STAR => 150000,
            self::DIAMOND => 300000,
            self::DIAMOND_STAR => 750000,
            self::ROYAL_DIAMOND => 1500000,
            self::CROWN => 3000000,
            self::CROWN_AMBASSADOR => 6500000,
        ];
    }

    public static function getUpgradeBonus($rank)
    {
        return [
            self::STAR => 60,
            self::SILVER_STAR => 100,
            self::PEARL_STAR => 250,
            self::GOLD_STAR => 450,
            self::EMERALD_STAR => 850,
            self::PLATINUM_STAR => 2100,
            self::ROYAL_STAR => 5100,
            self::DIAMOND => 10200,
            self::DIAMOND_STAR => 22500,
            self::ROYAL_DIAMOND => 46500,
            self::CROWN => 96000,
            self::CROWN_AMBASSADOR => 200000,
        ][$rank] ?? 0;
    }
}
