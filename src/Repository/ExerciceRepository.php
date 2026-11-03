<?php

namespace App\Repository;

use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Enum\StatutExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercice>
 */
class ExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercice::class);
    }

    /**
     * Exercice en cours d'un groupement (null s'il n'y en a pas).
     */
    public function findEnCours(Groupement $groupement): ?Exercice
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.groupement = :groupement')
            ->andWhere('e.statut = :statut')
            ->setParameter('groupement', $groupement)
            ->setParameter('statut', StatutExercice::EN_COURS)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}