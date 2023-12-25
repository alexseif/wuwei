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

//    /**
//     * @return TimeSystem[] Returns an array of TimeSystem objects
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

//    public function findOneBySomeField($value): ?TimeSystem
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
