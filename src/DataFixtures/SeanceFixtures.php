<?php

namespace App\DataFixtures;

use App\Entity\Epargne;
use App\Entity\Pret;
use App\Entity\Seance;
use App\Repository\CampagneRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CampagneRepository $repo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $datesEpargne = ['2026-01-04', '2026-01-11', '2026-01-18', '2026-01-25'];
        $datesTontine = ['2026-01-03', '2026-02-07', '2026-03-07', '2026-04-04'];

        foreach ($this->repo->findAll() as $campagne) {
            // Un fonds de prêt n'a pas de séance de cotisation
            if ($campagne instanceof Pret) {
                continue;
            }

            $dates = $campagne instanceof Epargne ? $datesEpargne : $datesTontine;

            foreach ($dates as $date) {
                $seance = (new Seance())
                    ->setDateSeance(new \DateTime($date))
                    ->setCampagne($campagne);

                $manager->persist($seance);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampagneFixtures::class,
        ];
    }
}