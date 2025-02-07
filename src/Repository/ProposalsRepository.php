<?php

namespace App\Repository;

use App\Entity\Proposals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proposals|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proposals|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proposals[]    findAll()
 * @method Proposals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProposalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposals::class);
    }

    // Add custom methods here if needed
}
