<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ParentDashboard
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard() {

        return new Response('oui');
    }

}