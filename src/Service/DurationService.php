<?php

namespace App\Service;

class DurationService
{
    public static function duration($start,$end)
    {
        // supprimer les tirets de la date et calcule la durée
        $total = str_replace('-', '', $end) - str_replace('-', '', $start);
        
        return $total;
    }
}