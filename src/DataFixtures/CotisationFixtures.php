<?php

namespace App\DataFixtures;

use App\Entity\Cotisation;
use App\Enum\StatutPresence;
use App\Repository\ModeCotisationRepository;
use App\Repository\SeanceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CotisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SeanceRepository $seanceRepo,
        private readonly ModeCotisationRepository $modeRepo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->seanceRepo->findAll() as $indexSeance => $seance) {
            $campagne = $seance->getCampagne();
            $membres = $campagne->getExercice()->getGroupement()->getMembres();
            $position = 0;

            foreach ($membres as $membre) {
                $presence = match (($indexSeance + $position) % 6) {
                    0 => StatutPresence::ABSENT_REPRESENTE,
                    1 => StatutPresence::ABSENT_SANS_PAIEMENT,
                    default => StatutPresence::PRESENT,
                };

                if ($presence === StatutPresence::ABSENT_SANS_PAIEMENT) {
                    $montant = 0.0;
                    $contribCaisse = 0.0;
                } else {
                    $mode = $this->modeRepo->findOneBy([
                        'membre' => $membre,
                        'campagne' => $campagne,
                    ]);
                    $estLibre = $mode?->isLibre() ?? false;

                    $montant = $estLibre
                        ? $campagne->getMontant() * (1 + ($position % 3) * 0.5)
                        : $campagne->getMontant();

                    $contribCaisse = $presence === StatutPresence::PRESENT
                        ? $campagne->getContribCaissePresent()
                        : $campagne->getContribCaisseAbsent();
                }

                $cotisation = (new Cotisation())
                    ->setSeance($seance)
                    ->setMembre($membre)
                    ->setPresence($presence)
                    ->setMontant($montant)
                    ->setContribCaisse($contribCaisse);

                $manager->persist($cotisation);
                $position++;
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeanceFixtures::class,
            ModeCotisationFixtures::class,
        ];
    }
}