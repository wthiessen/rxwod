<?php

namespace App\Repository;

use App\Entity\LiftRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiftRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiftRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiftRecord[]    findAll()
 * @method LiftRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiftRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiftRecord::class);
    }

    // /**
    //  * @return LiftRecord[] Returns an array of LiftRecord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiftRecord
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
