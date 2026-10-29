<?php

namespace App\DTO;

use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Util\DateFr;

final class GroupementDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $telephone,
        public readonly string $adresse,
        public readonly bool $aUnExerciceEnCours,
        public readonly string $exerciceDateDebut,
        public readonly string $exerciceDateFin,
        public readonly string $exerciceStatut,
    ) {
    }

    /**
     * L'exercice en cours est résolu ici : la vue reçoit directement
     * des textes, jamais la collection d'exercices.
     */
    public static function fromEntity(Groupement $groupement, ?Exercice $exerciceEnCours): self
    {
        return new self(
            $groupement->getId(),
            $groupement->getNom(),
            $groupement->getTelephone(),
            $groupement->getAdresse() ?? '—',
            $exerciceEnCours !== null,
            DateFr::texte($exerciceEnCours?->getDateDebut()),
            DateFr::texte($exerciceEnCours?->getDateFin()),
            $exerciceEnCours?->getStatut()->label() ?? 'Aucun exercice en cours',
        );
    }
}