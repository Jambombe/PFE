<?php


namespace App\Controller;

use App\Entity\ChildUser;
use App\Entity\ParentUser;
use App\Entity\Quest;
use App\Form\ChildUserType;
use App\Form\QuestType;
use App\Service\LevelService;
use App\Service\QuestStatusService;
use App\Service\TrophyService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;
use Symfony\Component\Validator\Constraints\Date;


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
            $childUser = $childUsers[0];
        } else {
            $childUser = null;
        }

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
     * @return Response
     */
    public function createChild(Request $request, EntityManagerInterface $em) {

        $user = $this->getUser();

        $newChild = new ChildUser();
        $childForm = $this->createForm(ChildUserType::class, $newChild);

        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $newChild->setParent($user);

//            $encodedPassword = $encoder->encodePassword($newChild->getPassword(), PASSWORD_BCRYPT);
            $encodedPassword = password_hash($newChild->getPassword(), PASSWORD_BCRYPT);
            $newChild->setPassword($encodedPassword);

            $em->persist($newChild);
            $em->flush();

//            dump($this->generateUrl('one-child', ['adventurer' => $newChild->getPseudo()]));
            $this->redirectToRoute('one-child', ['adventurer' => $newChild->getPseudo()]);
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
     * @return Response
     * @throws \Exception
     */
    public function quests(Request $request, EntityManagerInterface $em, QuestStatusService $qs) {

        $newQuest = new Quest();
        $newQuest->setOwner($this->getUser());

        $questForm = $this->createForm(QuestType::class, $newQuest, ['parent' => $this->getUser()]);

        $questForm->handleRequest($request);

        if ($questForm->isSubmitted() && $questForm->isValid()) {


            // Met à jour le status de la quête en fonction de si l'enfant est déjà défini
            if ($newQuest->getChild()) {
                $newQuest->setStatus($qs->ASSIGNATED['s']);
                $newQuest->setAssignatedDate(new DateTime());
            }

            $em->persist($newQuest);
            $em->flush();
        }

        return $this->render(
            'parent-dashboard/pages/quests.html.twig',
            [
                'user' => $this->getUser(),
                'questForm' => $questForm->createView()
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
     * @Route("test")
     * @param TrophyService $ts
     * @return Response
     */
    public function testTrophy(TrophyService $ts) {
        $u = $this->getDoctrine()->getRepository(ChildUser::class)->find(3);

        $ts->lfNewTrophies($u);

        return new Response();
    }


}