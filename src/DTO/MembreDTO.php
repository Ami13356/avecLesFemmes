<?php

namespace App\DTO;

use App\Entity\ModeCotisation;

final class MembreDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nomComplet,
        public readonly string $telephone,
        public readonly string $adresse,
        public readonly string $role,
        public readonly string $modeCotisation,
    ) {
    }

    public static function fromModeCotisation(ModeCotisation $mode): self
    {
        $membre = $mode->getMembre();

        return new self(
            $membre->getId(),
            $membre->getNomComplet(),
            $membre->getTelephone(),
            $membre->getAdresse(),
            $membre->getRole()->getNomRole(),
            $mode->isLibre() ? 'Montant libre' : 'Montant fixe',
        );
    }
}