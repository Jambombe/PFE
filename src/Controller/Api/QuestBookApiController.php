<?php


namespace App\Controller\Api;


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