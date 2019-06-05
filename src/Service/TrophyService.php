<?php


namespace App\Service;


use App\Entity\ChildUser;
use App\Entity\Trophy;
use App\Repository\TrophyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;

class TrophyService
{
    const REACH_LEVEL = 0;

    const QUEST_SUCCESS = 1;
    const IMAGES_UNLOCKED = 2;

    private $em;
    private $ls;

    public function __construct(EntityManagerInterface $em, LevelService $ls)
    {
        $this->em = $em;
        $this->ls = $ls;
    }

    /**
     * Retourne le 1er trophée non réalisé de chaque catégorie pour l'enfant $childUser
     *
     * @param $childUser
     * @return Trophy[]
     */
    public function nextTrophiesOfCategories($childUser) {


        // Pour une même catégorie de Trophée (voir les constantes), on prend seulement le plus bas
        // non réalisé (car si le plus bas n'est pas réalisé, les suivants ne peuvent l'être)

        /** @var TrophyRepository $trophyRepo */
        $trophyRepo = $this->em->getRepository(Trophy::class);


        try {
//        /** @var Trophy $reachLevelTrophy */
            $reachLevelTrophy = $trophyRepo->getFirstTrophyOf(self::REACH_LEVEL, $childUser)->setMaxResults(1)->getOneOrNullResult();

            /** @var Trophy $questSuccessTrophy */
            $questSuccessTrophy = $trophyRepo->getFirstTrophyOf(self::QUEST_SUCCESS, $childUser)->setMaxResults(1)->getOneOrNullResult();

            /** @var Trophy $imagesUnlockedTrophy */
            $imagesUnlockedTrophy = $trophyRepo->getFirstTrophyOf(self::IMAGES_UNLOCKED, $childUser)->setMaxResults(1)->getOneOrNullResult();

            return [
                'reachLevel' => $reachLevelTrophy,
                'questSuccess' => $questSuccessTrophy,
                'imageUnlocked' => $imagesUnlockedTrophy,
            ];
        } catch (Exception $e) {}

        return null;
    }


    /**
     * Parcours les trophées et regarde si certaines conditions sont réunies
     * Crée l'association ChildUser - Trophy si un nouveau trophée est réalisé
     *
     * @param ChildUser $childUser
     */
    public function lfNewTrophies($childUser) {

        $trophies = $this->nextTrophiesOfCategories($childUser);

        // self::REACH_LEVEL
        if ($trophies['reachLevel']) {
            if (($this->ls->infosFromExp($childUser->getExp())['level']) >= $trophies['reachLevel']->getArgument()){
                $childUser->addTrophy($trophies['reachLevel']);
                $this->em->flush();
            }
        }

        // self::QUEST_SUCCESS
        if ($trophies['questSuccess']) {
            $succesQuests = 0;
            foreach ($childUser->getQuests() as $q) {
                $q->getStatus() !== QuestStatusService::VALIDATED ?: $succesQuests++;
            }

            // Ajout du trophee si condition remplie
            if ($succesQuests >= $trophies['questSuccess']->getArgument()) {
                $childUser->addTrophy($trophies['questSuccess']);
            }
        }

        // self::IMAGE_UNLOCKED
            if ($trophies['imageUnlocked']) {
                if (sizeof($childUser->getUnlockedImages()) >= $trophies['imageUnlocked']->getArgument()) {
                    $childUser->addTrophy($trophies['imageUnlocked']);
                }
            }
    }


}