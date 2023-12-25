<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Search for tags based on a given criteria.
     *
     * @param string $searchTerm The term to search for in tag names
     * @param int $limit Maximum number of results to return
     *
     * @return Tag[] Returns an array of Tag objects matching the search criteria
     */
    public function searchForTag(string $searchTerm, int $limit = 10): array
    {
        return $this->createQueryBuilder('t')
          ->andWhere('t.name LIKE :searchTerm')
          ->setParameter('searchTerm', '%' . $searchTerm . '%')
          ->orderBy('t.name', 'ASC')
          ->setMaxResults($limit)
          ->getQuery()
          ->getResult();
    }

}
