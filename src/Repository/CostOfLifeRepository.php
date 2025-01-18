<?php

namespace App\Repository;

use App\Entity\CostOfLife;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CostOfLife>
 */
class CostOfLifeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CostOfLife::class);
    }

    public function getTotalCostOfLifeForCurrentMonth(): int
    {

        return (int) $this->createQueryBuilder('col')
            ->select('SUM(col.value)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return CostOfLife[] Returns an array of CostOfLife objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CostOfLife
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
