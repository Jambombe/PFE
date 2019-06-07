<?php


namespace App\Controller;


use App\Entity\ChildUser;
use App\Entity\ProfileImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestBookController extends AbstractController
{

    /**
     * @Route("journal", name="quest-book")
     */
    public function journal() {

        // Temporaire : redirection auto si non loggé ne marche pas
        if ($redirect = $this->redirectIfNotLogged()) return $redirect;

        return $this->redirectToRoute('quest-book-profile');
    }

    /**
     * @Route("journal/profil", name="quest-book-profile")
     * @return Response
     */
    public function profile() {

        // Temporaire : redirection auto si non loggé ne marche pas
        if ($redirect = $this->redirectIfNotLogged()) return $redirect;

        $user = $this->getUser();

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

        // Temporaire : redirection auto si non loggé ne marche pas
        if ($redirect = $this->redirectIfNotLogged()) return $redirect;

//        $user = $this->getDoctrine()->getManager()->getRepository(ChildUser::class)->find(1);
        $user = $this->getUser();

        return $this->render(
            'child-dashboard/pages/quests.html.twig',
            [
                'child' => $user,
            ]
        );
    }


    /**
     * @Route("journal/boutique", name="quest-book-shop")
     */
    public function shop() {

        // Temporaire : redirection auto si non loggé ne marche pas
        if ($redirect = $this->redirectIfNotLogged()) return $redirect;

        $user = $this->getUser();

        return $this->render(
            'child-dashboard/pages/shop.html.twig',
            [
                'child' => $user,
//                'images' => $this->getDoctrine()->getRepository(ProfileImage::class)->findAll(),
                'images' => $this->getDoctrine()->getRepository(ProfileImage::class)->findAllSortedBy('requiredLevel'),
            ]
        );
    }

    /**
     * @return RedirectResponse
     */
    public function redirectIfNotLogged() {
        if (! $this->getUser()) {
            return $this->redirectToRoute('ouvrir-journal');
        } else {
            return null;
        }
    }

}
