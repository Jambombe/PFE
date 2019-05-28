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
            ->where('t.category = :category')
            ->setParameter(':category', $category)
            ->orderBy('t.argument')
        ;

        if (! $childUser->getTrophies()->isEmpty()) {
            $q
                ->andWhere($q->expr()->notIn('t', ':cht'))
                ->setParameter(':cht', $childUser->getTrophies());
        }

        return $q->getQuery();
        /*
         * Requete SQL avec table assiocative child_user_trophy
         *
         * SELECT t.*
         * FROM trophy t, child_user c
         * WHERE t.id NOT IN(
         *      SELECT ct.trophy_id
         *      FROM child_user_trophy ct
         *      WHERE ct.child_user_id = 1
         * );
         *
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
