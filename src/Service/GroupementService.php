<?php

namespace App\Service;

use App\DTO\GroupementDTO;
use App\Repository\ExerciceRepository;
use App\Repository\GroupementRepository;

class GroupementService
{
    public function __construct(
        private readonly GroupementRepository $groupementRepository,
        private readonly ExerciceRepository $exerciceRepository,
    ) {
    }

    /**
     * @return GroupementDTO[]
     */
    public function getListe(): array
    {
        $dtos = [];

        foreach ($this->groupementRepository->findAllTries() as $groupement) {
            $exercice = $this->exerciceRepository->findEnCours($groupement);
            $dtos[] = GroupementDTO::fromEntity($groupement, $exercice);
        }

        return $dtos;
    }
}