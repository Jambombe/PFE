<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestBookController extends AbstractController
{

    /**
     * @Route("journal", name="quest-book")
     * @return Response
     */
    public function journal() {

        return $this->render(
            'child-dashboard/base.html.twig',
            []
        );
    }

}