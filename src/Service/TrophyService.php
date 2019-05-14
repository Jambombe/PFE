<?php


namespace App\Service;


use App\Entity\ChildUser;
use App\Entity\Trophy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class TrophyService
{
    const REACH_LEVEL = 0;

    const QUEST_SUCCESS = 1;
    const QUEST_FAIL = 2;

    private $em;
    private $ls;

    public function __construct(EntityManagerInterface $em, LevelService $ls)
    {
        $this->em = $em;
        $this->ls = $ls;
    }


    /**
     * Parcours les trophées et regarde si certaines conditions sont réunies
     * Crée l'association ChildUser - Trophy si un nouveau trophée est réalisé
     *
     * @param ChildUser $childUser
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function lfNewTrophies($childUser) {

        // Pour une même catégorie de Trophée (voir les constantes), on prend seulement le plus bas
        // non réalisé (car si le plus bas n'est pas réalisé, les suivants ne peuvent l'être)

        $trophyRepo = $this->em->getRepository(Trophy::class);

        /** @var Trophy $reachLevelTrophy */
        $reachLevelTrophy = $trophyRepo->getFirstTrophyOf(self::REACH_LEVEL, $childUser)->setMaxResults(1)->getSingleResult();

        /** @var Trophy $questSuccessTrophy */
        $questSuccessTrophy = $trophyRepo->getFirstTrophyOf(self::QUEST_SUCCESS, $childUser)->setMaxResults(1)->getSingleResult();

        /** @var Trophy $questFailTrophy */
        $questFailTrophy = $trophyRepo->getFirstTrophyOf(self::QUEST_FAIL, $childUser)->setMaxResults(1)->getSingleResult();


        // self::REACH_LEVEL
        if (($this->ls->infosFromExp($childUser->getExp())['level']) >= $reachLevelTrophy->getArgument()){
            $reachLevelTrophy->addChild($childUser);
            $this->em->flush();
        }

        // self::QUEST_SUCCESS
//        if (/* nb quete succes */ >= $questSuccessTrophy->getArgument()){
//            $questSuccessTrophy->addChild($childUser);
//            $this->em->flush();
//        }

        // self::QUEST_FAIL
//        if (/* Nb quete fail */ >= $questFailTrophy->getArgument()){
//            $questFailTrophy->addChild($childUser);
//            $this->em->flush();
//        }
    }
}