<?php

namespace App\Service;

use App\DTO\AmendeDTO;
use App\DTO\CotisationDTO;
use App\DTO\SeanceDTO;
use App\Repository\AmendeRepository;
use App\Repository\CotisationRepository;
use App\Repository\SeanceRepository;

class SeanceService
{
    public function __construct(
        private readonly SeanceRepository $seanceRepository,
        private readonly CotisationRepository $cotisationRepository,
        private readonly AmendeRepository $amendeRepository,
    ) {
    }

    /**
     * Séances d'une campagne avec leurs cotisations, leurs amendes
     * et les trois totaux de chaque séance.
     *
     * @return SeanceDTO[]
     */
    public function getSeancesDeLaCampagne(int $campagneId): array
    {
        // Une requête par type de donnée, puis regroupement par séance
        $cotisationsParSeance = [];
        foreach ($this->cotisationRepository->findByCampagne($campagneId) as $cotisation) {
            $dto = CotisationDTO::fromEntity($cotisation);
            $cotisationsParSeance[$dto->seanceId][] = $dto;
        }

        $amendesParSeance = [];
        foreach ($this->amendeRepository->findByCampagne($campagneId) as $amende) {
            $dto = AmendeDTO::fromEntity($amende);
            $amendesParSeance[$dto->seanceId][] = $dto;
        }

        $dtos = [];
        foreach ($this->seanceRepository->findByCampagne($campagneId) as $seance) {
            $cotisations = $cotisationsParSeance[$seance->getId()] ?? [];
            $amendes = $amendesParSeance[$seance->getId()] ?? [];

            $dtos[] = SeanceDTO::fromEntity(
                $seance,
                $cotisations,
                $amendes,
                array_sum(array_map(fn (CotisationDTO $c) => $c->montant, $cotisations)),
                array_sum(array_map(fn (AmendeDTO $a) => $a->montant, $amendes)),
                array_sum(array_map(fn (CotisationDTO $c) => $c->contribCaisse, $cotisations)),
            );
        }

        return $dtos;
    }
}