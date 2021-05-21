<?php

namespace App\Repository;

use App\Entity\Dicestatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dicestatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dicestatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dicestatistic[]    findAll()
 * @method Dicestatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DicestatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dicestatistic::class);
    }

    /**
     * @return int
     */
    public function getTotalDiceRolls(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT SUM(s.occurrence) as total
            FROM App\Entity\Dicestatistic s
            '
        );
        
        // returns an array of Product objects
        $result = $query->getResult();
        return $result[0]['total'];
    }
    // /**
    //  * @return Dicestatistic[] Returns an array of Dicestatistic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dicestatistic
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
