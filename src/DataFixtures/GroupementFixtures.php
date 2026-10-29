<?php

namespace App\DataFixtures;

use App\Entity\Groupement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupements = [
            ['Groupement Hospitalité', '771000001', 'Médina, Dakar'],
            ['Groupement Espoir', '772000002', 'Rufisque'],
            ['Groupement Les Femmes Unies', '773000003', null],
        ];

        foreach ($groupements as [$nom, $tel, $adresse]) {
            $groupement = (new Groupement())
                ->setNom($nom)
                ->setTelephone($tel)
                ->setAdresse($adresse);

            $manager->persist($groupement);
        }

        $manager->flush();
    }
}