<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
