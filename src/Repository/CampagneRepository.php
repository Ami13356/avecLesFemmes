<?php

namespace App\Repository;

use App\Entity\Campagne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campagne>
 */
class CampagneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campagne::class);
    }

    /**
     * Toutes les campagnes d'un groupement (via ses exercices), les plus récentes d'abord.
     *
     * @return Campagne[]
     */
    public function findByGroupement(int $groupementId): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.exercice', 'e')
            ->innerJoin('e.groupement', 'g')
            ->addSelect('e', 'g')
            ->andWhere('g.id = :groupementId')
            ->setParameter('groupementId', $groupementId)
            ->orderBy('c.dateDebut', 'DESC')
            ->addOrderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Une campagne avec son exercice et son groupement déjà chargés.
     */
    public function findOneAvecGroupement(int $id): ?Campagne
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.exercice', 'e')
            ->innerJoin('e.groupement', 'g')
            ->addSelect('e', 'g')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}