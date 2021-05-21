<?php

namespace App\Repository;

use App\Entity\Winpergame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Winpergame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Winpergame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Winpergame[]    findAll()
 * @method Winpergame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinpergameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Winpergame::class);
    }

    // /**
    //  * @return Winpergame[] Returns an array of Winpergame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Winpergame
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
