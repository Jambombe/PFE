<?php

namespace App\Repository;

use App\Entity\CustomReward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CustomReward|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomReward|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomReward[]    findAll()
 * @method CustomReward[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomRewardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomReward::class);
    }

    // /**
    //  * @return CustomReward[] Returns an array of CustomReward objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomReward
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
