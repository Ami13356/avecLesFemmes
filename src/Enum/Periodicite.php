<?php

namespace App\Enum;

enum Periodicite: string
{
    case HEBDOMADAIRE = 'hebdomadaire';
    case BIMENSUELLE = 'bimensuelle';
    case MENSUELLE = 'mensuelle';
    case QUINZAINE = 'quinzaine';

    public function label(): string
    {
        return match($this) {
            self::HEBDOMADAIRE => 'Hebdomadaire',
            self::BIMENSUELLE => 'Bimensuelle',
            self::MENSUELLE => 'Mensuelle',
            self::QUINZAINE => 'Par quinzaine',
        };
    }
}