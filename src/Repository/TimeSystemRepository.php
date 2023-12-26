<?php

namespace App\Repository;

use App\Entity\TimeSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSystem>
 *
 * @method TimeSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSystem[]    findAll()
 * @method TimeSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSystemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSystem::class);
    }

    public function getCurrent()
    {
        return $this->createQueryBuilder('ts')
          ->where('CURRENT_TIMESTAMP() BETWEEN ts.from_time AND ts.to_time')
          ->getQuery()
          ->getResult();
    }

}
