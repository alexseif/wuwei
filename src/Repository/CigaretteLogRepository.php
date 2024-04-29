<?php

namespace App\Repository;

use App\Entity\CigaretteLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CigaretteLogRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CigaretteLog::class);
    }

    public function countByDay(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('DATE(c.createdAt) as day, COUNT(c) as count')
          ->groupBy('day')
          ->orderBy('day', 'DESC')
          ->getQuery()
          ->getResult();
    }

    public function countByCurrentDayAndTime(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('COUNT(c) as count')
          ->where('c.createdAt >= :startOfCurrentDay')
          ->andWhere('c.createdAt <= :currentTime')
          ->setParameter('startOfCurrentDay', new \DateTime('today'))
          ->setParameter('currentTime', new \DateTime('now'))
          ->getQuery()
          ->getSingleResult();
    }

    public function countByPreviousDayAndTime(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('COUNT(c) as count')
          ->where('c.createdAt >= :startOfPreviousDay')
          ->andWhere('c.createdAt <= :sameTimeYesterday')
          ->setParameter('startOfPreviousDay', new \DateTime('yesterday'))
          ->setParameter(
            'sameTimeYesterday',
            (new \DateTime('yesterday'))->modify('+' . date('H') . ' hours')
          )
          ->getQuery()
          ->getSingleResult();
    }

    public function findByLastFiveDaysGroupedByDay(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('DATE(c.createdAt) as day, c.createdAt as timestamp')
          ->where('c.createdAt >= :fiveDaysAgo')
          ->setParameter('fiveDaysAgo', new \DateTime('-5 days'))
          ->orderBy('c.createdAt', 'ASC')
          ->getQuery()
          ->getResult();
    }

}