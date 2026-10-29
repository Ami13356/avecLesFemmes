<?php

namespace App\Repository;

use App\Entity\Groupement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupement>
 */
class GroupementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupement::class);
    }

    /**
     * @return Groupement[]
     */
    public function findAllTries(): array
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}