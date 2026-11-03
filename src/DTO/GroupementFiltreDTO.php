<?php

namespace App\DTO;

/**
 * DTO d'entrée : les valeurs saisies dans le formulaire de filtre.
 * Les propriétés sont publiques et modifiables : c'est le formulaire qui les remplit.
 */
class GroupementFiltreDTO
{
    public ?string $telephone = null;

    public ?string $ville = null;
}