<?php

namespace App\Repository;

use App\Entity\Quest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quest[]    findAll()
 * @method Quest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quest::class);
    }

    /**
     * Retourne un array de Quete de statut $status appartenant au childUser $childUser
     * @param $status
     * @param $childUser
     * @return Query
     */
    public function getQuestsFromStatus($status, $childUser) {

        $qb = $this->createQueryBuilder('q');
        $qb
            ->where($qb->expr()->eq('q.status', ':status'))
            ->andWhere($qb->expr()->eq('q.child', ':child'))
            ->setParameter('status', $status)
            ->setParameter('child', $childUser)
        ;

        return $qb->getQuery();
    }

    // /**
    //  * @return Quest[] Returns an array of Quest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quest
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
