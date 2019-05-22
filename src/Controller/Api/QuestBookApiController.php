<?php


namespace App\Controller\Api;


use App\Entity\ChildUser;
use App\Entity\CustomReward;
use App\Entity\Notification;
use App\Entity\Quest;
use App\Service\QuestStatusService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class QuestBookApiController extends AbstractController
{

    /**
     * L'enfant retourne sa quête après l'avoir (normalement) terminée
     * Passe du statut 1 au 2
     *
     * @Route("/api/c/return/{questId}", name="return-quest")
     * @param $questId
     * @return JsonResponse
     * @throws Exception
     */
    public function returnQuest($questId) {
        $user = $this->getUser();

        $em = $this->getDoctrine();

        $quest = $em->getRepository(Quest::class)->find($questId);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($user) {
            if ($quest) {
//                if ($quest->getChild() === $user) {
                if ($quest->getChild() !== $user) { // Pour tester avec le mauvais utilisateur

                    if ($quest->getStatus() === QuestStatusService::ASSIGNATED) {

                        // On change le statut de la quête
                        $quest->setStatus(QuestStatusService::RETURNED);
                        $quest->setReturnDate(new DateTime());

                        $notif = new Notification();
                        $notif->setTitle($quest->getChild()->getName() . " a rendu une quête !");
                        $notif->setMessage($quest->getChild()->getName() . " a rendu la quête \"". $quest->getTitle() ."\"");
                        $notif->setParentUsers($quest->getOwner());
                        $notif->setType(0);

                        $em->getManager()->persist($notif);

                        $em->getManager()->flush();

                        $message = "Quête rendue avec succès";
                        $responseCode = JsonResponse::HTTP_OK;
                    } else {
                        // Quete ayant un status différent de Assigné
                        $message = "Impossible de rendre cette quête";
                    }
                } else {
                    // User existant mais mauvais
                    $message = "Vous n'avez pas l'autorisation de rendre cette quête (mauvais utilisateur)";
                }
            } else {
                // Quete n'existe pas
                $message = "Impossible de rendre cette quête";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation de rendre cette quête (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }

    /**
     * L'enfant retourne sa quête après l'avoir (normalement) terminée
     * Passe du statut 1 au 2
     *
     * @Route("/api/c/buy/{rewardId}", name="buy-reward")
     * @param $rewardId
     * @return JsonResponse
     * @throws Exception
     */
    public function buyReward($rewardId) {

        /** @var ChildUser $child */
        $child = $this->getUser();
        $child = $this->getDoctrine()->getRepository(ChildUser::class)->find(1);

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($child) {

            $em = $this->getDoctrine();
            $reward = $em->getRepository(CustomReward::class)->find($rewardId);

            if ($reward) {
                if ($child->getParent() === $reward->getRewardOwner()) {

                    if ($child->getGoldCoins() >= $reward->getGoldCoinPrice()) {

                        // On retire les pièces d'or à l'enfant
                        $child->addGoldCoins(-$reward->getGoldCoinPrice());

                        $notif = new Notification();
                        $notif->setTitle($child->getName() . " a acheté une récompense !");
                        $notif->setMessage($child->getName() . " a acheté la récompense \"". $reward->getName() ."\"");
                        $notif->setParentUsers($child->getParent());
                        $notif->setType(0);

                        $em->getManager()->persist($notif);

                        $em->getManager()->flush();

                        $message = "Récompense achetée avec succès";
                        $responseCode = JsonResponse::HTTP_OK;
                    } else {
                        // Quete ayant un status différent de Assigné
                        $message = "Vous n'avez pas assez de pièce d'or pour acheter cette récompense";
                    }
                } else {
                    // User existant mais mauvais (enfant n'appartient pas au parent de la récompense)
                    $message = "Vous n'avez pas l'autorisation d'acheter cette récompense (mauvais utilisateur)";
                }
            } else {
                // Reward n'existe pas
                $message = "Impossible d'acheter cette récompense";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation d'acheter cette récompense (mauvais utilisateur)";
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