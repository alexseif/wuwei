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
          ->getQuery()
          ->getResult();
    }

}