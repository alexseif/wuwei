<?php

namespace App\Repository;

use App\Entity\Daily;
use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findAllWithLists()
    {
        return $this->createQueryBuilder('i')
          ->select('i, il')
          ->innerJoin('i.list', 'i')
          ->getQuery()
          ->getResult();
    }

    public function findItemsNotInDaily(Daily $daily): array
    {
        $queryBuilder = $this->createQueryBuilder('item');

        $queryBuilder
          ->andWhere('item.list = :list')
          ->setParameter('list', 4)
          ->andWhere('item.daily IS NULL OR item.daily != :daily')
          ->setParameter('daily', $daily);

        return $queryBuilder->getQuery()->getResult();
    }

}
