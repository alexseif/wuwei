<?php

namespace App\Repository;

use App\Entity\Requirements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Requirements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requirements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requirements[]    findAll()
 * @method Requirements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequirementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Requirements::class);
    }

    // Add custom methods here if needed
}
