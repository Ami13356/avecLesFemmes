<?php

namespace App\DTO;

use App\Entity\Cotisation;

final class CotisationDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $seanceId,
        public readonly string $membre,
        public readonly string $presence,
        public readonly float $montant,
        public readonly float $contribCaisse,
    ) {
    }

    public static function fromEntity(Cotisation $cotisation): self
    {
        return new self(
            $cotisation->getId(),
            $cotisation->getSeance()->getId(),
            $cotisation->getMembre()->getNomComplet(),
            $cotisation->getPresence()->label(),
            $cotisation->getMontant(),
            $cotisation->getContribCaisse(),
        );
    }
}