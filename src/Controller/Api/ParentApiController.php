<?php


namespace App\Controller\Api;


use App\Entity\CustomReward;
use App\Entity\Notification;
use App\Entity\Quest;
use App\Service\LevelService;
use App\Service\QuestStatusService;
use App\Service\TrophyService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ParentApiController extends AbstractController
{

    /**
     * Valider la quête
     *
     * @Route("/api/p/valid/{questId}", name="valid-quest")
     * @param $questId
     * @param LevelService $ls
     * @param TrophyService $ts
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function validQuest($questId, LevelService $ls, TrophyService $ts) {
        $user = $this->getUser();

        $em = $this->getDoctrine();

        /** @var Quest $quest */
        $quest = $em->getRepository(Quest::class)->find($questId);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($user) {
            if ($quest) {
                if ($quest->getOwner() === $user) {
                    if ($quest->getStatus() === QuestStatusService::RETURNED) {

                        // On change le statut de la quête
                        $quest->setStatus(QuestStatusService::VALIDATED);

                        // Enfant quête
                        $child = $quest->getChild();

                        // Niveau actuel de l'enfant avant ajout de l'exp
                        $currentLevel = $ls->infosFromExp($child->getExp())['level'];

                        // Ajout de l'exp et des pièces d'or
                        $child
                            ->addExp($quest->getExp())
                            ->addGoldCoins($quest->getGoldCoins());

                        // Nouveau niveau après ajout de l'exp (peut ne pas avoir changé
                        $newCurrentLevel = $ls->infosFromExp($child->getExp())['level'];

                        // Ajout de n cristaux de niveaux (min = 0)
                        $child->addLevelCrystal($newCurrentLevel-$currentLevel);

                        // On regarde s'il y a de nouveaux Trophées à donner à l'enfant
                        $ts->lfNewTrophies($child);

                        // Sauvegarde
                        $em->getManager()->flush();

                        $message = "Quête validée avec succès";
                        $responseCode = JsonResponse::HTTP_OK;
                    } else {
                        // Quete ayant un status différent de Assigné
                        $message = "Impossible de valider cette quête";
                    }
                } else {
                    // User existant mais mauvais
                    $message = "Vous n'avez pas l'autorisation de valider cette quête (mauvais utilisateur)";
                }
            } else {
                // Quete n'existe pas
                $message = "Impossible de valider cette quête";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation de valider cette quête (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }

    /**
     * Refuser la quête
     *
     * @Route("/api/p/refuse/{questId}", name="refuse-quest")
     * @param $questId
     * @return JsonResponse
     * @throws Exception
     */
    public function refuseQuest($questId) {
        $user = $this->getUser();

        $em = $this->getDoctrine();

        $quest = $em->getRepository(Quest::class)->find($questId);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($user) {
            if ($quest) {
                if ($quest->getOwner() === $user) {
                    if ($quest->getStatus() === QuestStatusService::RETURNED) {

                        // On change le statut de la quête
                        $quest->setStatus(QuestStatusService::FAILED);
                        $em->getManager()->flush();

                        $message = "Quête refusée avec succès";
                        $responseCode = JsonResponse::HTTP_OK;
                    } else {
                        // Quete ayant un status différent de Assigné
                        $message = "Impossible de refuser cette quête";
                    }
                } else {
                    // User existant mais mauvais
                    $message = "Vous n'avez pas l'autorisation pour refuser cette quête (mauvais utilisateur)";
                }
            } else {
                // Quete n'existe pas
                $message = "Impossible de refuser cette quête";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation pour refuser cette quête (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }


    /**
     * Supprimer une reward
     *
     * @Route("/api/p/delete-reward/{rewardId}", name="delete-reward")
     * @param $rewardId
     * @return JsonResponse
     */
    public function deleteReward($rewardId) {
        $user = $this->getUser();

        $em = $this->getDoctrine();

        $reward = $em->getRepository(CustomReward::class)->find($rewardId);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($user) {
            if ($reward) {
                if ($reward->getRewardOwner() === $user) {
                    // On change le statut de la quête

                    $em->getManager()->remove($reward);
                    $em->getManager()->flush();

                    $message = "Récompense supprimée avec succès";
                    $responseCode = JsonResponse::HTTP_OK;
                } else {
                    // User existant mais mauvais
                    $message = "Vous n'avez pas l'autorisation de supprimer cette récompense (mauvais utilisateur)";
                }
            } else {
                // Quete n'existe pas
                $message = "Impossible de supprimer cette récompense (elle n'existe pas)";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation de supprimer cette récompense (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }

    /**
     * @Route("/api/p/reward/{id}", name="get-reward")
     * @param CustomReward $reward
     * @return JsonResponse
     */
    public function getReward(CustomReward $reward) {

//        $r = $this->getDoctrine()->getRepository(CustomReward::class)->find($id);

        $rewardJson = [
            'id' => $reward->getId(),
            'name' => $reward->getName(),
            'description' => $reward->getDescription(),
            'price' => $reward->getGoldCoinPrice(),
            'image' => $reward->getImage()
        ];

        return $this->getJsonResponse(200, $rewardJson);
    }

    /**
     * @Route("/api/p/modif-reward/{id}", name="modif-reward")
     * @param CustomReward $reward
     * @param Request $r
     * @return JsonResponse
     */
    public function modifReward(Request $r, CustomReward $reward) {

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($reward) {
            if ($reward->getRewardOwner() === $this->getUser()) {
                $newReward = json_decode($r->getContent(), true);

                $newReward['name'] === '' ?: $reward->setName($newReward['name']);
                $newReward['description'] === '' ?: $reward->setDescription($newReward['description']);
                $newReward['price'] === '' ?: $reward->setGoldCoinPrice($newReward['price']);
                $newReward['image'] === '' ?: $reward->setImage($newReward['image']);
//                $reward->setDescription($newReward['description']);
//                $reward->setGoldCoinPrice($newReward['price']);
//                $reward->setImage($newReward['image']);

                $this->getDoctrine()->getManager()->flush();
                $responseCode = JsonResponse::HTTP_OK;
                $message = "Récompense modifée avec succès !";
            } else {
                $message = "Cette récompenses ne vius appartient pas";
            }
        } else {
            $message = "Cette récompenses personnalisée n'existe pas";
        }

        return $this->getJsonResponse($responseCode, $message);
    }

    /**
     * Supprimer une notif
     *
     * @Route("/api/p/delete-notif/{notifId}", name="delete-notif")
     * @param $notifId
     * @return JsonResponse
     */
    public function deleteNotif($notifId) {
        $user = $this->getUser();

        $em = $this->getDoctrine();

        $notif = $em->getRepository(Notification::class)->find($notifId);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($user) {
            if ($notif) {
                if ($notif->getParentUsers() === $user) {
                    // On change le statut de la quête

                    $em->getManager()->remove($notif);
                    $em->getManager()->flush();

                    $message = "Notification supprimée avec succès";
                    $responseCode = JsonResponse::HTTP_OK;
                } else {
                    // User existant mais mauvais
                    $message = "Vous n'avez pas l'autorisation de supprimer cette notification (mauvais utilisateur)";
                }
            } else {
                // Quete n'existe pas
                $message = "Impossible de supprimer cette notification";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation de supprimer cette notification (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }



    private function getJsonResponse($responseCode, $message) {
        return new JsonResponse(
            [
                'code' => $responseCode,
                'message' => $message,
            ],
            $responseCode
        );
    }

}
