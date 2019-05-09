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

        $user = $this->getDoctrine()->getManager()->getRepository(ChildUser::class)->find(3);

        return $this->render(
            'child-dashboard/pages/profile.html.twig',
            [
                'child' => $user,
            ]
        );
    }

}