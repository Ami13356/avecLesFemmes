<?php

namespace App\Composer;

use Composer\Script\Event;

/**
 * Commandes Composer personnalisées (préfixe aminatacisse).
 *
 * Sous Windows, Composer ne transmet pas le clavier (stdin) aux commandes qu'il
 * lance depuis "scripts". Résultat : make:entity ne peut pas poser ses questions
 * et s'arrête avec "Aborted.". Ici, on lance make:entity depuis PHP en lui
 * donnant directement le clavier et l'écran du terminal.
 */
class CommandesAminata
{
    public static function makeEntity(Event $event): int
    {
        // Arguments éventuels : composer aminatacisse:make:entity -- Campagne
        $arguments = $event->getArguments();

        $commande = array_merge([PHP_BINARY, 'bin/console', 'make:entity'], $arguments);

        $descripteurs = [
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR,
        ];

        $processus = proc_open($commande, $descripteurs, $pipes, getcwd());

        if (!is_resource($processus)) {
            $event->getIO()->writeError('<error>Impossible de lancer make:entity.</error>');

            return 1;
        }

        return proc_close($processus);
    }
}