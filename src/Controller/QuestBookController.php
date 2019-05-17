<?php


namespace App\Controller;


use App\Entity\ChildUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestBookController extends AbstractController
{

    /**
     * @Route("journal", name="quest-book")
     */
    public function journal() {

        return $this->redirectToRoute('quest-book-profile');
    }

    /**
     * @Route("journal/profil", name="quest-book-profile")
     * @return Response
     */
    public function profile() {

        $user = $this->getDoctrine()->getManager()->getRepository(ChildUser::class)->find(1);

        return $this->render(
            'child-dashboard/pages/profile.html.twig',
            [
                'child' => $user,
            ]
        );
    }

    /**
     * @Route("journal/quetes", name="quest-book-quests")
     * @return Response
     */
    public function quests() {

        $user = $this->getDoctrine()->getManager()->getRepository(ChildUser::class)->find(1);

        return $this->render(
            'child-dashboard/pages/questsv2.html.twig',
            [
                'child' => $user,
            ]
        );
    }


    /**
     * @Route("journal/boutique", name="quest-book-shop")
     */
    public function shop() {

        $user = $this->getDoctrine()->getManager()->getRepository(ChildUser::class)->find(1);

        return $this->render(
            'child-dashboard/pages/shop.html.twig',
            [
                'child' => $user,
            ]
        );
    }

}
