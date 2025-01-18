<?php

namespace App\Repository;

use App\Entity\AccountTransactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountTransactions>
 */
class AccountTransactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountTransactions::class);
    }

    public function getPaginatorQuery(?array $criteria = [])
    {
        $query = $this->createQueryBuilder('at')
            ->select('at, a, c')
            ->leftJoin('at.account', 'a')
            ->leftJoin('a.client', 'c');

        foreach ($criteria as $key => $value) {
            $parameterName = str_replace('.', '', $key);
            if ($value) {
                $query->where("$key = :$parameterName")
                    ->setParameter($parameterName, $value);
            }
        }

        return $query->getQuery();
    }

    public function getTotalIncomeForCurrentMonth(): int
    {
        $startOfMonth = new \DateTime('first day of this month');
        // $startOfMonth = new \DateTime('first day of last year');
        $endOfMonth = new \DateTime('last day of this month 23:59:59');
        // $endOfMonth = new \DateTime('last year 23:59:59');

        return (int) $this->createQueryBuilder('at')
            ->select('SUM(at.amount)')
            ->where('at.issuedAt BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTransactionsForCurrentMonth(): array
    {
        $startOfMonth = new \DateTime('first day of this month');
        // $startOfMonth = new \DateTime('first day of last year');
        $endOfMonth = new \DateTime('last day of this month 23:59:59');
        // $endOfMonth = new \DateTime('last year 23:59:59');

        return $this->createQueryBuilder('at')
            ->where('at.issuedAt BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->orderBy('at.issuedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return AccountTransactions[] Returns an array of AccountTransactions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AccountTransactions
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
