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
