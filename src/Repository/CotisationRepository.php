<?php

namespace App\Repository;

use App\Entity\Cotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cotisation>
 */
class CotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisation::class);
    }

    /**
     * Toutes les cotisations des séances d'une campagne (membre chargé avec).
     *
     * @return Cotisation[]
     */
    public function findByCampagne(int $campagneId): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.seance', 's')
            ->innerJoin('c.membre', 'm')
            ->addSelect('s', 'm')
            ->andWhere('s.campagne = :campagneId')
            ->setParameter('campagneId', $campagneId)
            ->orderBy('s.dateSeance', 'ASC')
            ->addOrderBy('m.nomComplet', 'ASC')
            ->getQuery()
            ->getResult();
    }
}