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

    /**
     * @return Score[]
     */
    public function showTopTenWins(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Winpergame s
            ORDER BY s.bet_won DESC
            '
        );
        $query->setMaxResults(10);
        // returns an array of Product objects
        return $query->getResult();
    }

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
