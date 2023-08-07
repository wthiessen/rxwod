<?php

namespace App\Repository;

use App\Entity\Wod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wod|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wod|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wod[]    findAll()
 * @method Wod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wod::class);
    }

    public function findOneNextId($value): ?Wod
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id > :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOnePreviousId($value): ?array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id < :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

}