<?php

namespace App\Repository;

use App\Entity\Daily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Daily>
 *
 * @method Daily|null findOneBy(array $criteria, array $orderBy = null)
 * @method Daily[]    findAll()
 * @method Daily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Daily::class);
    }

    protected function select()
    {
        return $this->createQueryBuilder('d')
          ->select('d', 'i')
          ->leftJoin('d.items', 'i');
    }

    /**
     * Overriding default function find to always innerjoin Items
     *
     * @param $id
     * @param $lockMode
     * @param $lockVersion
     *
     * @return \App\Entity\Daily|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Daily
    {
        $queryBuilder = $this->select();
        $queryBuilder
          ->where('d.id = :id')
          ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function getLastDaily(): ?Daily
    {
        $query = $this->select()
          ->where('d.id = (SELECT MAX(d2.id) FROM App\Entity\Daily d2)')
          ->getQuery();

        return $query->getOneOrNullResult();
    }

}
