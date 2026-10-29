<?php

namespace App\Enum;

enum StatutPresence: string
{
    case PRESENT = 'present';
    case ABSENT_REPRESENTE = 'absent_represente';
    case ABSENT_SANS_PAIEMENT = 'absent_sans_paiement';

    public function label(): string
    {
        return match($this) {
            self::PRESENT => 'Présent',
            self::ABSENT_REPRESENTE => 'Absent (représenté)',
            self::ABSENT_SANS_PAIEMENT => 'Absent (sans paiement)',
        };
    }
}