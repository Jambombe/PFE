<?php


namespace App\Controller;

use App\Entity\ChildUser;
use App\Entity\CustomReward;
use App\Entity\ParentUser;
use App\Entity\ProfileImage;
use App\Entity\Quest;
use App\Entity\Trophy;
use App\Form\ChildUserType;
use App\Form\CustomRewardType;
use App\Form\ModifyUserType;
use App\Form\QuestType;
use App\Service\LevelService;
use App\Service\QuestStatusService;
use App\Service\TrophyService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class ParentDashboard
 * @package App\Controller
 */
class ParentDashboard extends AbstractController
{

    /**
     * @Route("/dashboard")
     * @return RedirectResponse
     */
    public function dashboardHome() {
        return $this->redirectToRoute('dashboard');
    }

    /**
     * Affiche tous les enfants du parent connecté
     *
     * @Route("/dashboard/mes-enfants", name="dashboard")
     */
    public function dashboard() {

        $parentUser = $this->getUser();

        return $this->render(
            'parent-dashboard/pages/my-children.html.twig',
            [
                'user' => $parentUser,
            ]
        );
    }


    /**
     * Affiche le profil d'un enfant en particulier
     *
     * @Route("/dashboard/e/{adventurer}", name="one-child")
     * @param $adventurer
     * @param Request $request
     * @return Response
     */
    public function oneChild($adventurer, Request $request) {

        $childUsers = $this->getDoctrine()->getRepository(ChildUser::class)->findByPseudo($adventurer);

        if (sizeof($childUsers) > 0) {
            /** @var ChildUser $childUser */
            $childUser = $childUsers[0];
        } else {
            /** @var ChildUser $childUser */
            $childUser = null;
        }

        /** @var ParentUser $user */
        $user = $this->getUser();

        if (! $user->getChildren()->contains($childUser)) {
            $childUser = null;
        }

        if ($childUser) {
            $childUser->setPassword('');
            $childForm = $this->createForm(ChildUserType::class, $childUser, array('method'=>'put'));

            $childForm->handleRequest($request);
        } else {
            $childForm = null;
        }

        if ($childForm && $childForm->isSubmitted() && $childForm->isValid())
        {

            $encodedPassword = password_hash($childUser->getPassword(), PASSWORD_BCRYPT);
            $childUser->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

//            return $this->redirectToRoute('dashboard');
        }

        if ($childForm && $childForm->isSubmitted() && ! $childForm->isValid())
        {
            $childUsers = $this->getDoctrine()->getRepository(ChildUser::class)->findByPseudo($adventurer);

            if (sizeof($childUsers) > 0) {
                $childUser = $childUsers[0];
            } else {
                $childUser = null;
            }

        }

        return $this->render(
            'parent-dashboard/pages/one-child.html.twig',
            [
                'user' => $user,
                'child' => $childUser,
                'childForm' => $childForm ? $childForm->createView() : null,
            ]
        );

    }

    /**
     * Ajout d'un nouvel enfant
     *
     * @Route("dashboard/nouvel-enfant", name="dashboard-add-child")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TrophyService $ts
     * @return Response
     */
    public function createChild(Request $request, EntityManagerInterface $em, TrophyService $ts) {

        $user = $this->getUser();

        $newChild = new ChildUser();
        $childForm = $this->createForm(ChildUserType::class, $newChild);

        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $newChild->setParent($user);

//            $encodedPassword = $encoder->encodePassword($newChild->getPassword(), PASSWORD_BCRYPT);
            $encodedPassword = password_hash($newChild->getPassword(), PASSWORD_BCRYPT);
            $newChild->setPassword($encodedPassword);

            // Ajout de l'image par defaut
            /** @var ProfileImage $image */
            $image = $em->getRepository(ProfileImage::class)->find(20);
            $newChild->setProfileImage($image);
            $newChild->addUnlockedImage($image);

            // Assignation Trophées de base
//            $newChild->addTrophy($em->getRepository(Trophy::class))
            $ts->lfNewTrophies($newChild);

            $em->persist($newChild);
            $em->flush();

            return $this->redirectToRoute('one-child', ['adventurer' => $newChild->getPseudo()]);
        }

        return $this->render(
            'parent-dashboard/pages/addChild.html.twig',
            [
                'user' => $user,
                'childForm' => $childForm->createView(),
            ]
        );
    }

    /**
     * Dashboard des quêtes - Permet de voir les quetes en cours, à valider en d'en créer de nouvelles
     *
     * @Route("dashboard/quetes", name="dashboard-quests")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param QuestStatusService $qs
     * @param LevelService $ls
     * @return Response
     * @throws \Exception
     */
    public function quests(Request $request, EntityManagerInterface $em, QuestStatusService $qs, LevelService $ls) {

        /** @var ParentUser $user */
        $user = $this->getUser();

        if (! empty($user->getChildren())) {

            $newQuest = new Quest();
            $newQuest->setOwner($this->getUser());

            $questForm = $this->createForm(QuestType::class, $newQuest, ['parent' => $this->getUser(), 'ls' => $ls]);

            $questForm->handleRequest($request);

            if ($questForm->isSubmitted() && $questForm->isValid()) {

                // Met à jour le status de la quête en fonction de si l'enfant est déjà défini
                if ($newQuest->getChild()) {
                    $newQuest->setStatus($qs->ASSIGNATED['s']);
                    $newQuest->setAssignatedDate(new DateTime());
                } else {
                    $childRow = $questForm->get('child');
                    $childRow->addError(new FormError("Veuillez sélectionner un enfant"));
                }

                $em->persist($newQuest);
                $em->flush();

                return $this->redirectToRoute('one-child', ['adventurer' => $newQuest->getChild()->getPseudo()]);
            }
        }

        return $this->render(
            'parent-dashboard/pages/quests.html.twig',
            [
                'user' => $user,
                'questForm' => !$questForm?:$questForm->createView(),
            ]
        );
    }

    /**
     * Dashboard des récompenses perso
     *
     * @Route("dashboard/recompenses", name="dashboard-custom-rewards")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function customRewards(Request $request, EntityManagerInterface $em) {

        /** @var ParentUser $user */
        $user = $this->getUser();

        $newCustomReward = new CustomReward();
        $newCustomReward->setRewardOwner($user);

        $customRewardForm = $this->createForm(CustomRewardType::class, $newCustomReward);

        $customRewardForm->handleRequest($request);

        if ($customRewardForm->isSubmitted() && $customRewardForm->isValid()) {

            if ($newCustomReward->getNbUnitAvailable() === null) {
                $newCustomReward->setNbUnitAvailable(-1);
            }

            if (! $newCustomReward->getImage() || ! file_exists($newCustomReward->getImage())) {
//                $path = $assetsManager->getUrl('assets/img/home/white-image.png');
                $path = 'https://cdn3.iconfinder.com/data/icons/fantasy-and-role-play-game-adventure-quest/512/King-512.png';
                $newCustomReward->setImage($path);
//                {{ asset('book_icon.PNG', 'home_img') }}
            }

            $em->persist($newCustomReward);
            $em->flush();
        }

        return $this->render(
            'parent-dashboard/pages/custom-rewards.html.twig',
            [
                'user' => $user,
                'customRewardForm' => $customRewardForm->createView()
            ]
        );
    }


    /**
     * Dashboard des notifications
     *
     * @Route("dashboard/notifications", name="dashboard-notifications")
     */
    public function notifications() {

        return $this->render(
            'parent-dashboard/pages/notifications.html.twig',
            [
                'user' => $this->getUser(),
            ]
        );

    }

    /**
     * Modifier les infos de son compte Parent
     *
     * @Route("dashboard/options", name="parent-options")
     * @param Request $request
     * @param UserPasswordEncoderInterface $pe
     * @return Response
     */
    public function options(Request $request, UserPasswordEncoderInterface $pe) {

        /** @var ParentUser $user */
        $user = $this->getUser();

        $optionForm = $this->createForm(ModifyUserType::class, $user);

        $optionForm->handleRequest($request);

        if ($optionForm->isSubmitted() && $optionForm->isValid()) {

            $currentPasswordField = $optionForm->get('currentPassword');

            // Le mot de passe actuel correspond à celui saisi par l'utilisateur
            if ($pe->isPasswordValid($user, $currentPasswordField->getData())) {

                $password = $pe->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success',"Informations changées avec succès");

            } else {
                $currentPasswordField->addError(new FormError("Le mot de passe actuel est incorrect"));
            }

        }

        return $this->render(
            'parent-dashboard/pages/options.html.twig',
            [
                'user' => $user,
                'optionsForm' => $optionForm->createView(),
            ]
        );
    }

    /**
     * @Route("test")
     * @param TrophyService $ts
     * @return Response
     */
    public function testTrophy(TrophyService $ts) {
        /** @var ChildUser $u */
        $u = $this->getDoctrine()->getRepository(ChildUser::class)->find(1);

        $ts->lfNewTrophies($u);

        return new Response();
    }
}
