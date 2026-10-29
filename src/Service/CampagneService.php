<?php

namespace App\Service;

use App\DTO\CampagneDTO;
use App\DTO\MembreDTO;
use App\Repository\CampagneRepository;
use App\Repository\GroupementRepository;
use App\Repository\ModeCotisationRepository;

class CampagneService
{
    public function __construct(
        private readonly CampagneRepository $campagneRepository,
        private readonly GroupementRepository $groupementRepository,
        private readonly ModeCotisationRepository $modeCotisationRepository,
    ) {
    }

    /**
     * Nom du groupement, ou null s'il n'existe pas (le contrôleur renvoie alors une 404).
     */
    public function getNomGroupement(int $groupementId): ?string
    {
        return $this->groupementRepository->find($groupementId)?->getNom();
    }

    /**
     * @return CampagneDTO[]
     */
    public function getCampagnesDuGroupement(int $groupementId): array
    {
        return array_map(
            CampagneDTO::fromEntity(...),
            $this->campagneRepository->findByGroupement($groupementId),
        );
    }

    public function getCampagne(int $campagneId): ?CampagneDTO
    {
        $campagne = $this->campagneRepository->findOneAvecGroupement($campagneId);

        return $campagne === null ? null : CampagneDTO::fromEntity($campagne);
    }

    /**
     * @return MembreDTO[]
     */
    public function getMembresDeLaCampagne(int $campagneId): array
    {
        return array_map(
            MembreDTO::fromModeCotisation(...),
            $this->modeCotisationRepository->findByCampagne($campagneId),
        );
    }
}