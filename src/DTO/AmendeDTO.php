<?php

namespace App\DTO;

use App\Entity\Amende;

final class AmendeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $seanceId,
        public readonly string $membre,
        public readonly int $nbJours,
        public readonly float $montant,
        public readonly bool $estPayee,
    ) {
    }

    public static function fromEntity(Amende $amende): self
    {
        return new self(
            $amende->getId(),
            $amende->getSeance()->getId(),
            $amende->getMembre()->getNomComplet(),
            $amende->getNbJours(),
            $amende->getMontant(),
            $amende->isEstPayee(),
        );
    }
}