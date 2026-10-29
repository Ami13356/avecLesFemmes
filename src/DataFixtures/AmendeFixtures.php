<?php

namespace App\DataFixtures;

use App\Entity\Amende;
use App\Enum\StatutPresence;
use App\Repository\CotisationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AmendeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CotisationRepository $repo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $absentsSansPaiement = $this->repo->findBy([
            'presence' => StatutPresence::ABSENT_SANS_PAIEMENT,
        ]);

        foreach ($absentsSansPaiement as $index => $cotisation) {
            $seance = $cotisation->getSeance();
            $nbJours = 2 + ($index % 3);

            $amende = (new Amende())
                ->setSeance($seance)
                ->setMembre($cotisation->getMembre())
                ->setNbJours($nbJours)
                ->setMontant($nbJours * $seance->getCampagne()->getAmendeJournaliere())
                ->setEstPayee($index % 2 === 0);

            $manager->persist($amende);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CotisationFixtures::class,
        ];
    }
}