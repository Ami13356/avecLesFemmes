<?php

namespace App\Repository;

use App\Entity\Amende;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amende>
 */
class AmendeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amende::class);
    }

    /**
     * Toutes les amendes des séances d'une campagne (membre chargé avec).
     *
     * @return Amende[]
     */
    public function findByCampagne(int $campagneId): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.seance', 's')
            ->innerJoin('a.membre', 'm')
            ->addSelect('s', 'm')
            ->andWhere('s.campagne = :campagneId')
            ->setParameter('campagneId', $campagneId)
            ->orderBy('s.dateSeance', 'ASC')
            ->addOrderBy('m.nomComplet', 'ASC')
            ->getQuery()
            ->getResult();
    }
}