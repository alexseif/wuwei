<?php

namespace App\Repository;

use App\Entity\Timelog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timelog>
 */
class TimelogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timelog::class);
    }

    public function findLastTimelog(): ?Timelog
    {
        return $this->findOneBy([], ['start' => 'DESC']);
    }
}
