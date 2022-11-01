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
            case 1:
                return 'START';
            case 2:
                return 'SILVER START';
            case 3:
                return 'PEARL START';
            case 4:
                return 'GOLD START';
            case 5:
                return 'EMERALD START';
            case 6:
                return 'PLATINUM START';
            case 7:
                return 'DIAMOND START';
            case 8:
                return 'ROYAL DIAMOND';
            default:
                return 'N/A';
        }
    }

    public static function getRankAmountMap()
    {
        return [0, 450, 1350, 4050, 12150, 36450, 109350, 328050, 984150, 2952450];
    }
}
