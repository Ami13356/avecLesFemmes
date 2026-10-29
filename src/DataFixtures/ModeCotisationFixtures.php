<?php

namespace App\DataFixtures;

use App\Entity\ModeCotisation;
use App\Entity\Pret;
use App\Repository\CampagneRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModeCotisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CampagneRepository $repo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->repo->findAll() as $campagne) {
            // Un fonds de prêt n'a pas de mode de cotisation
            if ($campagne instanceof Pret) {
                continue;
            }

            $groupement = $campagne->getExercice()->getGroupement();
            $position = 0;

            foreach ($groupement->getMembres() as $membre) {
                $mode = (new ModeCotisation())
                    ->setIsLibre($position % 3 === 2)
                    ->setMembre($membre)
                    ->setCampagne($campagne);

                $manager->persist($mode);
                $position++;
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampagneFixtures::class,
            MembreFixtures::class,
        ];
    }
}