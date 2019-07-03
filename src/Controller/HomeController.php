<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController {


    /**
     * @Route("/", name="home")
     */
    public function home() {


        return $this->render(
            'home/pages/home.html.twig',
            []
        );
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation() {

        return $this->render(
            'home/pages/presentation.html.twig',
            []
        );
    }

    /**
     * @Route("/sitemap", name="sitemap")
     * @return Response
     */
    public function sitemap() {
        return $this->render(
            'home/pages/sitemap.html.twig',
            []
        );
    }

    /**
     * @Route("/mentions_legales", name="mentions_legales")
     * @return Response
     */
    public function legale_mentions() {
        return $this->render(
            'home/pages/legale_mentions.html.twig',
            []
        );
    }

}
