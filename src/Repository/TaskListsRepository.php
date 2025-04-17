<?php

namespace App\Repository;

use App\Entity\TaskLists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskLists>
 */
class TaskListsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskLists::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('tl')
            ->select('tl, a, c')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->orderBy('tl.status', 'DESC')
            ->addOrderBy('tl.order', 'ASC')
            ->addOrderBy('tl.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getActiveTaskLists()
    {
        return $this->createQueryBuilder('tl')
            ->select('tl, a, c')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->where('tl.status <> \'archive\'')
        ;
    }
    //    /**
    //     * @return TaskLists[] Returns an array of TaskLists objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TaskLists
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
