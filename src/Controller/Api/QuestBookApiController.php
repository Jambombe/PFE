<?php


namespace App\Controller\Api;


use App\Entity\ChildUser;
use App\Entity\CustomReward;
use App\Entity\Notification;
use App\Entity\ProfileImage;
use App\Entity\Quest;
use App\Service\LevelService;
use App\Service\QuestStatusService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journal/api/c/")
 */
class QuestBookApiController extends AbstractController
{

    /**
     * L'enfant retourne sa quête après l'avoir (normalement) terminée
     * Passe du statut 1 au 2
     *
     * @Route("return/{questId}", name="return-quest")
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
     * @Route("buy/{rewardId}", name="buy-reward")
     * @param $rewardId
     * @return JsonResponse
     * @throws Exception
     */
    public function buyReward($rewardId) {

        /** @var ChildUser $child */
        $child = $this->getUser();
//        $child = $this->getDoctrine()->getRepository(ChildUser::class)->find(1);

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

    /**
     * L'enfant achete une image en échange de cristaux de niveau
     *
     * @Route("buy-image/{imageId}", name="buy-image")
     * @param $imageId
     * @param LevelService $ls
     * @return JsonResponse
     */
    public function buyImage($imageId, LevelService $ls) {

        /** @var ChildUser $child */
        $child = $this->getUser();

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($child) {

            $em = $this->getDoctrine();
            /** @var ProfileImage $image */
            $image = $em->getRepository(ProfileImage::class)->find($imageId);

            if ($image) {
                if (! $child->getUnlockedImages()->contains($image)) {

                    if ($child->getLevelCrystal() >= $image->getPrice()) {

                        if ($ls->infosFromExp($child->getExp())['level'] >= $image->getRequiredLevel()) {

                            // On retire les cristaux de niveau à l'enfant
                            $child->addLevelCrystal(-$image->getPrice());

                            // Ajout de l'image aux image débloquées
                            $child->addUnlockedImage($image);
                            $child->setProfileImage($image);

                            $notif = new Notification();
                            $notif->setTitle($child->getName() . " a acheté une nouvelle image !");
                            $notif->setMessage($child->getName() . " a acheté \"" . $image->getName() . "\" pour " . $image->getPrice() . " cristaux de niveau");
                            $notif->setParentUsers($child->getParent());
                            $notif->setType(0);

                            $em->getManager()->persist($notif);

                            $em->getManager()->flush();

                            $message = "Image achetée avec succès";
                            $responseCode = JsonResponse::HTTP_OK;

                        } else {
                            // Pas le niveau requis
                            $message = "Vous n'avez pas le niveau requis pour acheter cette image";
                        }
                    } else {
                        // Pas assez de cristaux
                        $message = "Vous n'avez pas assez de cristaux de niveau pour acheter cette image";
                    }
                } else {
                    // User possède déjà cette image
                    $message = "Vous possédez déjà cette image";
                }
            } else {
                // image n'existe pas
                $message = "Impossible d'acheter cette image";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation d'acheter cette image (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message);
    }

    /**
     * L'enfant change d'image de profile parmi celles débloquées
     *
     * @Route("change-image/{imageId}", name="change-image")
     * @param $imageId
     * @return JsonResponse
     * @throws Exception
     */
    public function changeImage($imageId) {

        /** @var ChildUser $child */
        $child = $this->getUser();

        $responseCode = JsonResponse::HTTP_FORBIDDEN;

        if ($child) {

            $em = $this->getDoctrine();
            /** @var ProfileImage $image */
            $image = $em->getRepository(ProfileImage::class)->find($imageId);

            if ($image) {
                if ($child->getUnlockedImages()->contains($image)) {

                    // Ajout de l'image aux image débloquées
                    $child->setProfileImage($image);
                    $em->getManager()->flush();

                    $message = "Image changée avec succès";
                    $responseCode = JsonResponse::HTTP_OK;
                } else {
                    // User possède déjà cette image
                    $message = "Vous devez acheter cette image avant de l'utiliser";
                }
            } else {
                // image n'existe pas
                $message = "Impossible d'acheter cette image";
            }
        } else {
            // User inexistant / non connecté
            $message = "Vous n'avez pas l'autorisation d'acheter cette image (mauvais utilisateur)";
        }

        return $this->getJsonResponse($responseCode, $message, $child);
    }

    private function getJsonResponse($responseCode, $message, $user = 'oui') {
        return new JsonResponse(
            [
                'code' => $responseCode,
                'message' => $message,
                'user' => $user,
            ],
            $responseCode
        );
    }

}