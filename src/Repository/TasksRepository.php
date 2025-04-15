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
            ->orderBy('t.completed', 'ASC')
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

    public function getFocusTasks($limit = 6)
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
            ->setMaxResults($limit)
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

    public function getCompletedToday()
    {
        $today = new \DateTime('today');
        $today->setTime(0, 0, 0);

        return $this->createQueryBuilder('t')
            ->select('t, tl, a, c')
            ->leftJoin('t.taskList', 'tl')
            ->leftJoin('tl.account', 'a')
            ->leftJoin('a.client', 'c')
            ->where('t.completed = true')
            ->where('t.completedAt >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }

    // ... existing code ...

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

    public function getWeeklyWorkHours(): array
    {
        $startOfWeek = new \DateTime('last sunday');
        $startOfWeek->setTime(0, 0, 0);

        $endOfWeek = new \DateTime('next saturday');
        $endOfWeek->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('t')
            ->select('DATE(t.completedAt) as day, SUM(t.duration) as totalMinutes')
            ->where('t.completed = true')
            ->andWhere('t.completedAt BETWEEN :startOfWeek AND :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->groupBy('day')
            ->orderBy('day', 'ASC');

        $results = $qb->getQuery()->getResult();

        // Define daily goals (in minutes)
        $dailyGoals = [
            'Sunday' => 240,
            'Monday' => 240,
            'Tuesday' => 240,
            'Wednesday' => 240,
            'Thursday' => 240,
            'Friday' => 0,
            'Saturday' => 0,
        ];

        // Format the results into percentages
        $weekData = [];
        foreach ($dailyGoals as $day => $goal) {
            $weekData[$day] = 0; // Default to 0%
        }

        foreach ($results as $result) {
            $date = new \DateTime($result['day']);
            $dayName = $date->format('l'); // Get the day name (e.g., "Sunday")
            $totalMinutes = $result['totalMinutes'];
            $goal = $dailyGoals[$dayName];
            $weekData[$dayName] = $goal > 0 ? round(($totalMinutes / $goal) * 100, 2) : 0;
        }

        return $weekData;
    }
    // ... existing code ...
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
