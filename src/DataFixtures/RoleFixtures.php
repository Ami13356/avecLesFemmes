<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = ['Présidente', 'Secrétaire', 'Trésorière', 'Membre'];

        foreach ($roles as $nomRole) {
            $role = (new Role())->setNomRole($nomRole);

            $manager->persist($role);
        }

        $manager->flush();
    }
}