<?php

namespace App\Lib;

class Rank
{
    public const START = 0;
    public const SILVER_START = 1;
    public const PEARL_START = 2;
    public const GOLD_START = 3;
    public const EMERALD_START = 4;
    public const PLATINUM_START = 5;
    public const DIAMOND_START = 6;
    public const ROYAL_DIAMOND = 7;

    public static function getRankName($rank)
    {
        switch ($rank) {
            case null:
                return 'N/A';
            case 0:
                return 'START';
            case 1:
                return 'SILVER START';
            case 2:
                return 'PEARL START';
            case 3:
                return 'GOLD START';
            case 4:
                return 'EMERALD START';
            case 5:
                return 'PLATINUM START';
            case 6:
                return 'DIAMOND START';
            case 7:
                return 'ROYAL DIAMOND';
            default:
                return 'N/A';
        }
    }

    public static function getRankAmountMap()
    {
        return [450, 1350, 4050, 12150, 36450, 109350, 328050, 984150, 2952450];
    }
}
