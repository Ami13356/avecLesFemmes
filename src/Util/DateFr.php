<?php

namespace App\Util;

/**
 * Convertit une date en texte français (ex. 4 janvier 2026).
 * Utilisé par les DTO : la vue ne manipule jamais d'objet date.
 */
final class DateFr
{
    private const MOIS = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
    ];

    public static function texte(?\DateTimeInterface $date): string
    {
        if ($date === null) {
            return '—';
        }

        $jour = (int) $date->format('j');
        $mois = self::MOIS[(int) $date->format('n')];

        return sprintf('%s %s %s', $jour === 1 ? '1er' : $jour, $mois, $date->format('Y'));
    }
}