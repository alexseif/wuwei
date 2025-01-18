<?php

namespace App\Repository;

use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tasks>
 */
class TasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasks::class);
    }

    public function getPaginatorQuery(?array $criteria = [])
    {
        $query = $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c');

        foreach ($criteria as $key => $value) {
            $parameterName = str_replace('.', '', $key);
            if (!is_null($value)) {
                $query->andWhere("$key = :$parameterName")
                    ->setParameter($parameterName, $value);
                if ($key === 't.completed') {
                    $query->orWhere('DATE(t.completedAt) = DATE(:today)')
                        ->setParameter('today', new \DateTime());
                }
            }
        }
        $query
            ->addOrderBy('t.urgency', 'DESC')
            ->addOrderBy('t.priority', 'DESC')
            ->addOrderBy('t.order', 'ASC')
        ;

        return $query->getQuery();
    }

    public function getPaginatorResult(?array $criteria = []): array
    {
        return $this->getPaginatorQuery($criteria)->getResult();
    }

    public function getFocusTasks()
    {
        return  $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->where('t.completed = false')
            ->orderBy('t.urgency', 'DESC')
            ->addOrderBy('t.priority', 'DESC')
            ->addOrderBy('t.order', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function getFocusDayTasks()
    {
        return  $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->where('t.completed = false')
            ->orderBy('t.urgency', 'DESC')
            ->addOrderBy('t.priority', 'DESC')
            ->addOrderBy('t.order', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getCreatedToday()
    {
        $today = new \DateTime('today');
        $today->setTime(0, 0, 0);

        return $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->where('t.createdAt >= :today')
            ->setParameter('today', $today)
            ->orderBy('t.urgency', 'DESC')
            ->addOrderBy('t.priority', 'DESC')
            ->addOrderBy('t.order', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Tasks[] Returns an array of Tasks objects
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

    //    public function findOneBySomeField($value): ?Tasks
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
