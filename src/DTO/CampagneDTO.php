<?php

namespace App\DTO;

use App\Entity\Campagne;
use App\Entity\Epargne;
use App\Entity\Pret;
use App\Entity\Tontine;
use App\Util\DateFr;

final class CampagneDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $type,
        public readonly string $dateDebut,
        public readonly string $dateFin,
        public readonly float $montant,
        public readonly string $periodicite,
        public readonly string $jourRencontre,
        public readonly float $contribCaissePresent,
        public readonly float $contribCaisseAbsent,
        public readonly float $amendeJournaliere,
        public readonly int $groupementId,
        public readonly string $groupementNom,
        public readonly string $exerciceStatut,
        public readonly bool $aDesSeances,
    ) {
    }

    public static function fromEntity(Campagne $campagne): self
    {
        $exercice = $campagne->getExercice();
        $groupement = $exercice->getGroupement();

        return new self(
            $campagne->getId(),
            $campagne->getNom(),
            self::libelleType($campagne),
            DateFr::texte($campagne->getDateDebut()),
            DateFr::texte($campagne->getDateFin()),
            $campagne->getMontant(),
            $campagne->getPeriodicite()->label(),
            $campagne->getJourRencontre(),
            $campagne->getContribCaissePresent(),
            $campagne->getContribCaisseAbsent(),
            $campagne->getAmendeJournaliere(),
            $groupement->getId(),
            $groupement->getNom(),
            $exercice->getStatut()->label(),
            !$campagne instanceof Pret,
        );
    }

    private static function libelleType(Campagne $campagne): string
    {
        return match (true) {
            $campagne instanceof Epargne => 'Épargne',
            $campagne instanceof Tontine => 'Tontine',
            $campagne instanceof Pret => 'Prêt',
            default => 'Campagne',
        };
    }
}