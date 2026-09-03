<?php

namespace App\Helpers;

class KampungHelper
{
    public const KAMPUNG_RW_MAP = [
        'Timuran' => ['01', '02', '03'],
        'Brontokusuman' => ['04', '05', '06'],
        'Prawirotaman' => ['07', '08', '09'],
        'Karangkajen' => ['10', '11', '12', '13', '14', '15', '23'],
        'Karanganyar' => ['16', '17', '18', '19'],
        'Lowanu' => ['20', '21', '22'],
    ];

    public static function getAllRw(): array
    {
        $allRw = [];
        foreach (self::KAMPUNG_RW_MAP as $rws) {
            $allRw = array_merge($allRw, $rws);
        }
        sort($allRw);
        return $allRw;
    }

    public static function getKampungByRw(string $rw): ?string
    {
        foreach (self::KAMPUNG_RW_MAP as $kampung => $rws) {
            if (in_array($rw, $rws)) {
                return $kampung;
            }
        }
        return null;
    }

    public static function getRwByKampung(string $kampung): array
    {
        return self::KAMPUNG_RW_MAP[$kampung] ?? [];
    }

    public static function getKampungList(): array
    {
        return array_keys(self::KAMPUNG_RW_MAP);
    }

    public static function getGroupedRw(): array
    {
        return self::KAMPUNG_RW_MAP;
    }

    public static function getRwsByKampungs(array $kampungs): array
    {
        $rws = [];
        foreach ($kampungs as $kampung) {
            $rws = array_merge($rws, self::getRwByKampung($kampung));
        }
        sort($rws);
        return $rws;
    }
}
