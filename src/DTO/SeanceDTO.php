<?php

namespace App\DTO;

use App\Entity\Seance;
use App\Util\DateFr;

final class SeanceDTO
{
    /**
     * @param CotisationDTO[] $cotisations
     * @param AmendeDTO[]     $amendes
     */
    public function __construct(
        public readonly int $id,
        public readonly string $dateSeance,
        public readonly array $cotisations,
        public readonly array $amendes,
        public readonly float $totalCollecte,
        public readonly float $totalAmendes,
        public readonly float $totalCaisseSociale,
    ) {
    }

    /**
     * Les trois totaux sont calculés par le service (SeanceService)
     * et simplement transportés ici.
     *
     * @param CotisationDTO[] $cotisations
     * @param AmendeDTO[]     $amendes
     */
    public static function fromEntity(
        Seance $seance,
        array $cotisations,
        array $amendes,
        float $totalCollecte,
        float $totalAmendes,
        float $totalCaisseSociale,
    ): self {
        return new self(
            $seance->getId(),
            DateFr::texte($seance->getDateSeance()),
            $cotisations,
            $amendes,
            $totalCollecte,
            $totalAmendes,
            $totalCaisseSociale,
        );
    }
}