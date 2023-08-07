<?php

namespace App\Repository;

use App\Entity\Daily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Daily>
 *
 * @method Daily|null find($id, $lockMode = null, $lockVersion = null)
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

    public function getLastDaily(): ?Daily
    {
        $query = $this->select()
          ->where('d.id = (SELECT MAX(d2.id) FROM App\Entity\Daily d2)')
          ->getQuery();

        return $query->getOneOrNullResult();
    }

}
