<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\TimeSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSystem>
 *
 * @method TimeSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSystem|null findOneBy(array $criteria, array $orderBy = null)
// * @method TimeSystem[]    findAll()
 * @method TimeSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSystemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSystem::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('ts')
          ->select('ts, t, tt')
          ->leftJoin('ts.tags', 't')
          ->leftJoin('t.tagType', 'tt')
          ->getQuery()
          ->getResult();
    }

    public function findAllWithTagTypes()
    {
        $query = $this->getEntityManager()->createQuery(
          '
            SELECT ts.id, ts.name, ts.from_time as fromTime, ts.to_time as toTime, 
                   (MAX(CASE WHEN tt.name = \'Time System\' THEN t.name ELSE 0 END)) as time_system,
                   (MAX(CASE WHEN tt.name = \'Associated Qualities\' THEN t.name ELSE 0 END)) as associated_qualities,
                   (MAX(CASE WHEN tt.name = \'Energetic Qualities\' THEN t.name ELSE 0 END)) as energetic_qualities,
                   (MAX(CASE WHEN tt.name = \'Organ\' THEN t.name ELSE 0 END)) as organ,
                   (MAX(CASE WHEN tt.name = \'Element\' THEN t.name ELSE 0 END)) as element,
                   (MAX(CASE WHEN tt.name = \'Diety\' THEN t.name ELSE 0 END)) as diety,
                   (MAX(CASE WHEN tt.name = \'Dosha\' THEN t.name ELSE 0 END)) as dosha
            FROM App\Entity\TimeSystem ts
            JOIN ts.tags t 
            JOIN t.tagType tt
            GROUP BY ts.id
        '
        );

        return $query->getResult();
    }

    public function getCurrent()
    {
        return $this->createQueryBuilder('ts')
          ->where('CURRENT_TIMESTAMP() BETWEEN ts.from_time AND ts.to_time')
          ->getQuery()
          ->getResult();
    }

}
