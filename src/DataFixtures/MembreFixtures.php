<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\Repository\GroupementRepository;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MembreFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly GroupementRepository $groupementRepo,
        private readonly RoleRepository $roleRepo
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Mot de passe de test commun, stocké haché
        $motDePasse = password_hash('Passer123', PASSWORD_BCRYPT);

        $membres = [
            ['Fatou Sow', '771100001', 'Médina, Dakar', 'fatou.sow', 'Présidente', 'Groupement Hospitalité'],
            ['Mariama Ba', '771100002', 'Grand Yoff, Dakar', 'mariama.ba', 'Secrétaire', 'Groupement Hospitalité'],
            ['Khady Fall', '771100003', 'Parcelles Assainies, Dakar', 'khady.fall', 'Trésorière', 'Groupement Hospitalité'],
            ['Aïssatou Diallo', '771100004', 'Médina, Dakar', 'aissatou.diallo', 'Membre', 'Groupement Hospitalité'],
            ['Awa Ndiaye', '771100005', 'Fass, Dakar', 'awa.ndiaye', 'Membre', 'Groupement Hospitalité'],
            ['Ndèye Sarr', '772100001', 'Rufisque', 'ndeye.sarr', 'Présidente', 'Groupement Espoir'],
            ['Yacine Gueye', '772100002', 'Thiaroye, Dakar', 'yacine.gueye', 'Secrétaire', 'Groupement Espoir'],
            ['Rokhaya Mbaye', '772100003', 'Rufisque', 'rokhaya.mbaye', 'Trésorière', 'Groupement Espoir'],
            ['Binta Sy', '772100004', 'Bargny', 'binta.sy', 'Membre', 'Groupement Espoir'],
            ['Coumba Diop', '773100001', 'Pikine, Dakar', 'coumba.diop', 'Présidente', 'Groupement Les Femmes Unies'],
            ['Dior Thiam', '773100002', 'Guédiawaye', 'dior.thiam', 'Secrétaire', 'Groupement Les Femmes Unies'],
            ['Maty Faye', '773100003', 'Pikine, Dakar', 'maty.faye', 'Trésorière', 'Groupement Les Femmes Unies'],
        ];

        foreach ($membres as [$nomComplet, $tel, $adresse, $login, $nomRole, $nomGroupement]) {
            $membre = (new Membre())
                ->setNomComplet($nomComplet)
                ->setTelephone($tel)
                ->setAdresse($adresse)
                ->setLogin($login)
                ->setPassword($motDePasse)
                ->setRole($this->roleRepo->findOneBy(['nomRole' => $nomRole]))
                ->setGroupement($this->groupementRepo->findOneBy(['nom' => $nomGroupement]));

            $manager->persist($membre);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GroupementFixtures::class,
            RoleFixtures::class,
        ];
    }
}