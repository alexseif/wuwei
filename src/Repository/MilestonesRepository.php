<?php

namespace App\Repository;

use App\Entity\Milestones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Milestones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Milestones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Milestones[]    findAll()
 * @method Milestones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MilestonesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Milestones::class);
    }

    // Add custom methods here if needed
}
