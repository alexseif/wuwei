<?php

namespace App\Repository;

use App\Entity\WorkLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkLog>
 */
class WorkLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkLog::class);
    }
    public function findAll()
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.task', 't')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->getQuery()
            ->getResult();
    }
    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('w')
            ->leftJoin('w.task', 't')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c');

        if (!empty($filters['task'])) {
            $qb->andWhere('w.task IN (:tasks)')
                ->setParameter('tasks', $filters['task']);
        }

        // if (!empty($filters['taskList'])) {
        // $qb->andWhere('w.taskList IN (:taskLists)')
        // ->setParameter('taskLists', $filters['taskList']);
        // }

        // if (!empty($filters['account'])) {
        // $qb->andWhere('w.account IN (:accounts)')
        // ->setParameter('accounts', $filters['account']);
        // }

        // if (!empty($filters['client'])) {
        // $qb->andWhere('w.client IN (:clients)')
        // ->setParameter('clients', $filters['client']);
        // }

        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return WorkLog[] Returns an array of WorkLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WorkLog
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
