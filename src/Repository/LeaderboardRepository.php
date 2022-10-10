<?php

namespace App\Repository;

use App\Entity\Leaderboard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leaderboard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leaderboard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leaderboard[]    findAll()
 * @method Leaderboard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaderboardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leaderboard::class);
    }

    /**
     * @return Leaderboard[] Returns an array of Leaderboard objects
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
     * @return Leaderboard[] Returns an array of Leaderboard objects
     */
    public function findByWodId($value)
    {
        $data = $this->createQueryBuilder('l')
            ->andWhere('l.wod = :val')
            ->setParameter('val', $value)
            ->orderBy('l.rx', 'DESC')
            ->orderBy('l.score', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return $data;
    }

    /**
     * @return Leaderboard[] Returns an array of Leaderboard objects
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
