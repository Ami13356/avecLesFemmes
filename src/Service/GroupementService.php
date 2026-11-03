<?php

namespace App\Service;

use App\DTO\GroupementDTO;
use App\DTO\GroupementFiltreDTO;
use App\DTO\PaginationDTO;
use App\Repository\ExerciceRepository;
use App\Repository\GroupementRepository;

class GroupementService
{
    public const PAR_PAGE = 2;

    public function __construct(
        private readonly GroupementRepository $groupementRepository,
        private readonly ExerciceRepository $exerciceRepository,
    ) {
    }

    /**
     * La même fonction filtre, pagine et fournit la liste à afficher.
     */
    public function getListe(GroupementFiltreDTO $filtre, int $page): PaginationDTO
    {
        $page = max(1, $page);

        $resultat = $this->groupementRepository->findByFiltre(
            $filtre->telephone,
            $filtre->ville,
            $page,
            self::PAR_PAGE,
        );

        $dtos = [];
        foreach ($resultat as $groupement) {
            $exercice = $this->exerciceRepository->findEnCours($groupement);
            $dtos[] = GroupementDTO::fromEntity($groupement, $exercice);
        }

        $total = count($resultat);

        return new PaginationDTO(
            $dtos,
            $page,
            max(1, (int) ceil($total / self::PAR_PAGE)),
            $total,
            self::PAR_PAGE,
        );
    }
}