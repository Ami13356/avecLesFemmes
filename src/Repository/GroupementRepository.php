<?php

namespace App\Repository;

use App\Entity\Groupement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * Requête unique pour la liste : elle filtre (téléphone et/ou ville) ET pagine.
     * Remplace findAll(). Sans filtre, elle renvoie simplement la première page de tous les groupements.
     */
    public function findByFiltre(?string $telephone, ?string $ville, int $page, int $parPage): Paginator
    {
        $qb = $this->createQueryBuilder('g')
            ->orderBy('g.nom');

        if ($telephone !== null && trim($telephone) !== '') {
            $qb->andWhere('g.telephone LIKE :telephone')
                ->setParameter('telephone', '%' . addcslashes(trim($telephone), '%_\\') . '%');
        }

        if ($ville !== null && trim($ville) !== '') {
            $qb->andWhere('LOWER(g.ville) LIKE :ville')
                ->setParameter('ville', '%' . addcslashes(mb_strtolower(trim($ville)), '%_\\') . '%');
        }

        $qb->setFirstResult((max(1, $page) - 1) * $parPage)
            ->setMaxResults($parPage);

        return new Paginator($qb->getQuery(), false);
    }
}