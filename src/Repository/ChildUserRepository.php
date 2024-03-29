<?php

namespace App\Repository;

use App\Entity\ChildUser;
use App\Entity\ParentUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChildUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChildUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChildUser[]    findAll()
 * @method ChildUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChildUser::class);
    }

    public function getFromParent(ParentUser $parent) {

        return $this->createQueryBuilder('c')
            ->where('c.parent = :parent')
            ->setParameter(':parent', $parent)
            ->getQuery()
            ;
    }

    // /**
    //  * @return ChildUser[] Returns an array of ChildUser objects
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
    public function findOneBySomeField($value): ?ChildUser
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
