<?php

namespace App\DataFixtures;

use App\Entity\Campagne;
use App\Entity\Epargne;
use App\Entity\Pret;
use App\Entity\Tontine;
use App\Enum\Periodicite;
use App\Enum\StatutExercice;
use App\Repository\ExerciceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampagneFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly ExerciceRepository $repo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $exercicesEnCours = $this->repo->findBy(['statut' => StatutExercice::EN_COURS]);

        foreach ($exercicesEnCours as $exercice) {
            $campagnes = [
                $this->parametrer(new Epargne(), 'Épargne calebasse', 5000, Periodicite::HEBDOMADAIRE, 'Dimanche'),
                $this->parametrer(new Tontine(), 'Tontine mensuelle', 10000, Periodicite::MENSUELLE, 'Samedi'),
                $this->parametrer(new Pret(), 'Fonds de prêt solidaire', 50000, Periodicite::MENSUELLE, 'Samedi'),
            ];

            foreach ($campagnes as $campagne) {
                $campagne->setExercice($exercice);
                $manager->persist($campagne);
            }
        }

        $manager->flush();
    }

    private function parametrer(
        Campagne $campagne,
        string $nom,
        float $montant,
        Periodicite $periodicite,
        string $jourRencontre
    ): Campagne {
        return $campagne
            ->setNom($nom)
            ->setDateDebut(new \DateTimeImmutable('2026-01-04'))
            ->setDateFin(new \DateTime('2026-12-31'))
            ->setMontant($montant)
            ->setPeriodicite($periodicite)
            ->setJourRencontre($jourRencontre)
            ->setContribCaissePresent(100)
            ->setContribCaisseAbsent(300)
            ->setAmendeJournaliere(350);
    }

    public function getDependencies(): array
    {
        return [
            ExerciceFixtures::class,
        ];
    }
}