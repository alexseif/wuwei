<?php

namespace App\Repository;

use App\Entity\ItemList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemList>
 *
 * @method ItemList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemList[]    findAll()
 * @method ItemList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemList::class);
    }

public function findAllWithItems(){
        return $this->createQueryBuilder('il')
          ->select('il, i')
          ->innerJoin('il.items', 'i')
          ->where('il.id <> 4')
          ->getQuery()
          ->getResult();
}
}
