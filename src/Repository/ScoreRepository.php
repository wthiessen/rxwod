<?php

namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    /**
     * @return Score[] Returns an array of Score objects
     */
    public function findByDateCreated($value)
    {
        $data = $this->createQueryBuilder('l')
//            ->andWhere('l.dateCreated LIKE :val')
            ->andWhere('l.id = :val')
//            ->andWhere('o.Product LIKE :product')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;

        return $data;
    }

    /**
     * @return Score[] Returns an array of Score objects
     */
    public function findByWodId($value)
    {
        $data = $this->createQueryBuilder('l')
            ->andWhere('l.wod = :val')
            ->setParameter('val', $value)
            ->orderBy('l.score', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return $data;
    }

    /**
     * @return Score[] Returns an array of Score objects
     */
    public function findAllNames()
    {
        $data = $this->createQueryBuilder('l')
            ->select('DISTINCT l.name')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return $data;
    }
}
