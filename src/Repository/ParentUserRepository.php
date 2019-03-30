<?php

namespace App\Repository;

use App\Entity\ParentUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParentUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentUser[]    findAll()
 * @method ParentUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParentUser::class);
    }

    // /**
    //  * @return ParentUser[] Returns an array of ParentUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParentUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
