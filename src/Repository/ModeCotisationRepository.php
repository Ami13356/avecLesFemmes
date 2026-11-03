<?php

namespace App\Repository;

use App\Entity\ModeCotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModeCotisation>
 */
class ModeCotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeCotisation::class);
    }

    /**
     * Les membres d'une campagne sont ceux qui y ont un mode de cotisation.
     * On part de ModeCotisation pour récupérer aussi le mode (fixe ou libre).
     *
     * @return ModeCotisation[]
     */
    public function findByCampagne(int $campagneId): array
    {
        return $this->createQueryBuilder('mc')
            ->innerJoin('mc.membre', 'm')
            ->innerJoin('m.role', 'r')
            ->addSelect('m', 'r')
            ->andWhere('mc.campagne = :campagneId')
            ->setParameter('campagneId', $campagneId)
            ->orderBy('m.nomComplet', 'ASC')
            ->getQuery()
            ->getResult();
    }
}