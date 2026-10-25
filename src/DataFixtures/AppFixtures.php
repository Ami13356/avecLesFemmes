<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\DemandePret;
use App\Entity\Epargne;
use App\Entity\Groupement;
use App\Entity\Membre;
use App\Entity\ModeCotisation;
use App\Entity\Pret;
use App\Entity\Role;
use App\Entity\Tontine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private string $motDePasse;

    public function load(ObjectManager $manager): void
    {
        // Mot de passe commun de test, stocké haché
        $this->motDePasse = password_hash('Passer123', PASSWORD_BCRYPT);

        // ---- Rôles ----
        $presidente = (new Role())->setNomRole('Présidente');
        $secretaire = (new Role())->setNomRole('Secrétaire');
        $tresoriere = (new Role())->setNomRole('Trésorière');
        $simpleMembre = (new Role())->setNomRole('Membre');

        foreach ([$presidente, $secretaire, $tresoriere, $simpleMembre] as $role) {
            $manager->persist($role);
        }

        // ---- Groupements ----
        $teranga = (new Groupement())->setNom('Groupement Teranga');
        $yaakaar = (new Groupement())->setNom('Groupement Yaakaar');

        $manager->persist($teranga);
        $manager->persist($yaakaar);

        // ---- Admins (un par groupement) ----
        $adminTeranga = (new Admin())
            ->setNomComplet('Awa Ndiaye')
            ->setTelephone('770000001')
            ->setAdresse('Médina, Dakar')
            ->setLogin('awa.ndiaye')
            ->setPassword($this->motDePasse)
            ->setNiveauAcces('ADMIN')
            ->setGroupement($teranga);

        $adminYaakaar = (new Admin())
            ->setNomComplet('Binta Sy')
            ->setTelephone('770000002')
            ->setAdresse('Rufisque')
            ->setLogin('binta.sy')
            ->setPassword($this->motDePasse)
            ->setNiveauAcces('ADMIN')
            ->setGroupement($yaakaar);

        $manager->persist($adminTeranga);
        $manager->persist($adminYaakaar);

        // ---- Membres ----
        $fatou = $this->creerMembre($manager, 'Fatou Sow', '771000001', 'Médina, Dakar', 'fatou.sow', 'MBR-001', $presidente, $teranga);
        $mariama = $this->creerMembre($manager, 'Mariama Ba', '771000002', 'Grand Yoff, Dakar', 'mariama.ba', 'MBR-002', $secretaire, $teranga);
        $khady = $this->creerMembre($manager, 'Khady Fall', '771000003', 'Parcelles Assainies, Dakar', 'khady.fall', 'MBR-003', $tresoriere, $teranga);
        $aissatou = $this->creerMembre($manager, 'Aïssatou Diallo', '771000004', 'Médina, Dakar', 'aissatou.diallo', 'MBR-004', $simpleMembre, $teranga);
        $ndeye = $this->creerMembre($manager, 'Ndèye Sarr', '772000001', 'Rufisque', 'ndeye.sarr', 'MBR-005', $presidente, $yaakaar);
        $yacine = $this->creerMembre($manager, 'Yacine Gueye', '772000002', 'Thiaroye, Dakar', 'yacine.gueye', 'MBR-006', $secretaire, $yaakaar);
        $rokhaya = $this->creerMembre($manager, 'Rokhaya Mbaye', '772000003', 'Rufisque', 'rokhaya.mbaye', 'MBR-007', $simpleMembre, $yaakaar);

        // ---- Campagnes ----
        $epargneTabaski = (new Epargne())
            ->setNom('Épargne Tabaski')
            ->setDateDebut(new \DateTime('2026-01-05'))
            ->setDateFin(new \DateTime('2026-05-25'))
            ->setMontant(5000)
            ->setCreatedAt(new \DateTimeImmutable('2025-12-20'))
            ->setTauxInteret(2.5)
            ->setGroupement($teranga);

        $tontineMensuelle = (new Tontine())
            ->setNom('Tontine mensuelle')
            ->setDateDebut(new \DateTime('2026-02-01'))
            ->setMontant(10000)
            ->setCreatedAt(new \DateTimeImmutable('2026-01-15'))
            ->setOrdreTirage(1)
            ->setGroupement($teranga);

        $pretSolidaire = (new Pret())
            ->setNom('Fonds de prêt solidaire')
            ->setDateDebut(new \DateTime('2026-03-01'))
            ->setDateFin(new \DateTime('2026-12-31'))
            ->setMontant(200000)
            ->setCreatedAt(new \DateTimeImmutable('2026-02-15'))
            ->setTauxInteretPret(5.0)
            ->setDureeMaxMois(6)
            ->setTauxPenaliteRetard(2.0)
            ->setGroupement($teranga);

        $tontineHebdo = (new Tontine())
            ->setNom('Tontine hebdomadaire')
            ->setDateDebut(new \DateTime('2026-02-10'))
            ->setMontant(2000)
            ->setCreatedAt(new \DateTimeImmutable('2026-02-01'))
            ->setOrdreTirage(1)
            ->setGroupement($yaakaar);

        $epargneScolaire = (new Epargne())
            ->setNom('Épargne scolaire')
            ->setDateDebut(new \DateTime('2026-09-01'))
            ->setDateFin(new \DateTime('2027-06-30'))
            ->setMontant(3000)
            ->setCreatedAt(new \DateTimeImmutable('2026-08-20'))
            ->setTauxInteret(1.5)
            ->setGroupement($yaakaar);

        foreach ([$epargneTabaski, $tontineMensuelle, $pretSolidaire, $tontineHebdo, $epargneScolaire] as $campagne) {
            $manager->persist($campagne);
        }

        // ---- Modes de cotisation (false = montant fixe, true = montant libre) ----
        $this->creerMode($manager, $fatou, $epargneTabaski, false);
        $this->creerMode($manager, $mariama, $epargneTabaski, false);
        $this->creerMode($manager, $khady, $epargneTabaski, true);
        $this->creerMode($manager, $aissatou, $epargneTabaski, true);
        $this->creerMode($manager, $fatou, $tontineMensuelle, false);
        $this->creerMode($manager, $khady, $tontineMensuelle, false);
        $this->creerMode($manager, $ndeye, $tontineHebdo, false);
        $this->creerMode($manager, $yacine, $tontineHebdo, false);
        $this->creerMode($manager, $rokhaya, $epargneScolaire, true);

        // ---- Demandes de prêt ----
        $this->creerDemande($manager, $aissatou, $pretSolidaire, 50000, 'Achat de marchandises pour le commerce', 'EN_ATTENTE', '2026-04-01', '2026-09-01', null, false);
        $this->creerDemande($manager, $khady, $pretSolidaire, 80000, 'Frais de scolarité', 'VALIDEE', '2026-03-10', '2026-08-10', 1600.0, false);
        $this->creerDemande($manager, $fatou, $pretSolidaire, 30000, 'Réparation de la machine à coudre', 'VALIDEE', '2026-03-15', '2026-06-15', null, true);

        $manager->flush();
    }

    private function creerMembre(
        ObjectManager $manager,
        string $nomComplet,
        string $telephone,
        string $adresse,
        string $login,
        string $matricule,
        Role $role,
        Groupement $groupement
    ): Membre {
        $membre = (new Membre())
            ->setNomComplet($nomComplet)
            ->setTelephone($telephone)
            ->setAdresse($adresse)
            ->setLogin($login)
            ->setPassword($this->motDePasse)
            ->setMatricule($matricule)
            ->setRole($role)
            ->setGroupement($groupement);

        $manager->persist($membre);

        return $membre;
    }

    private function creerMode(ObjectManager $manager, Membre $membre, \App\Entity\Campagne $campagne, bool $isLibre): void
    {
        $mode = (new ModeCotisation())
            ->setIsLibre($isLibre)
            ->setMembre($membre)
            ->setCampagne($campagne);

        $manager->persist($mode);
    }

    private function creerDemande(
        ObjectManager $manager,
        Membre $membre,
        Pret $pret,
        float $montant,
        string $motif,
        string $statut,
        string $dateDemande,
        string $dateEcheance,
        ?float $montantAmende,
        bool $estRembourse
    ): void {
        $demande = (new DemandePret())
            ->setMontantDemande($montant)
            ->setMotif($motif)
            ->setStatut($statut)
            ->setDateDemande(new \DateTime($dateDemande))
            ->setDateEcheance(new \DateTime($dateEcheance))
            ->setMontantAmende($montantAmende)
            ->setEstRembourse($estRembourse)
            ->setMembre($membre)
            ->setPret($pret);

        $manager->persist($demande);
    }
}