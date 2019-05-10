<?php

namespace App\Repository;

use App\Entity\ChildUser;
use App\Entity\Trophy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * @method Trophy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trophy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trophy[]    findAll()
 * @method Trophy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrophyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trophy::class);
    }

    /**
     * Retourne le 1er Trophée non validé de $childUser dans la catégorie $category
     * @param $category
     * @param ChildUser $childUser
     * @return Query
     */
    public function getFirstTrophyOf($category, $childUser) {

//        $validatedTrophies = $childUser->getTrophies();

        $q = $this->createQueryBuilder('t');
        $q
            ->where($q->expr()->eq('t.category', ':category'))
            ->setParameter(':category', $category)


            // Et où $childUser NOT IN $trophy->getChildren()
//            ->andWhere($q->expr())

            ///////////////////////////////

            ->orderBy('t.argument')
            ;

        return $q->getQuery();

        /*
         * Requete pseudo SQL
         *
         * SELECT t
         * From Trophy t
         * Where t.category = $category
         * AND $childUser NOT IN t->getChildren()
         *
         */
    }

    // /**
    //  * @return Trophy[] Returns an array of Trophy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trophy
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
