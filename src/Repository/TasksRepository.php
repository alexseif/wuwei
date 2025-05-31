<?php

namespace App\Repository;

use App\Entity\TaskLists;
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
    public function getOrder($query)
    {
        $query->orderBy('t.completed', 'ASC')
            ->addOrderBy('t.urgency', 'DESC')
            ->addOrderBy('t.priority', 'DESC')
            ->addOrderBy('tl.order', 'ASC')
            ->addOrderBy('t.order', 'ASC');

        return $query;
    }
    public function getSelect()
    {
        return $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
        ;
    }

    public function getPaginatorQuery(?array $criteria = [])
    {
        $query = $this->getSelect();

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
        $query = $this->getOrder($query);

        return $query->getQuery();
    }

    public function getPaginatorResult(?array $criteria = []): array
    {
        return $this->getPaginatorQuery($criteria)->getResult();
    }

    public function getFocusTasks($limit = 6)
    {
        $query = $this->getSelect();
        $query->where('t.completed = false');
        $query = $this->getOrder($query);
        $query->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    public function getFocusDayTasks()
    {
        return $this->getFocusTasks(10);
    }

    public function getCreatedToday()
    {
        $today = new \DateTime('today');
        $today->setTime(0, 0, 0);
        $query = $this->getSelect();
        $query->where('t.createdAt >= :today')
            ->setParameter('today', $today);
        $query = $this->getOrder($query);
        return $query->getQuery()->getResult();
    }

    public function getCompletedToday()
    {
        $today = new \DateTime('today');
        $today->setTime(0, 0, 0);
        $query = $this->getSelect();
        $query->where('t.completed = true')
            ->andWhere('t.completedAt >= :today')
            ->setParameter('today', $today)
        ;
        $query = $this->getOrder($query);
        return $query->getQuery()->getResult();
    }


    public function getMaxOrderNotInList(TaskLists $taskLists): ?int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('MAX(t.order)') // Assuming 'order' is the field for ordering tasks
            ->where('t.taskList != :taskListId')
            ->setParameter('taskListId', $taskLists);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getProgressByTasklist(TaskLists $taskList): ?float
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id) as totalTasks, SUM(CASE WHEN t.completed = 1 THEN 1 ELSE 0 END) as completedTasks')
            ->where('t.taskList = :taskList')
            ->setParameter('taskList', $taskList)
            ->getQuery()
            ->getSingleResult();

        if ($qb['totalTasks'] == 0) {
            return 0;
        }

        return ($qb['completedTasks'] / $qb['totalTasks']) * 100;
    }
}
