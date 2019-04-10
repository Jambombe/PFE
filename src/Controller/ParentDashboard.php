<?php


namespace App\Controller;

use App\Entity\ChildUser;
use App\Entity\ParentUser;
use App\Form\ChildUserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ParentDashboard
 * @package App\Controller
 */
class ParentDashboard extends AbstractController
{

    /**
     * @Route("/dashboard/mes-enfants", name="dashboard")
     */
    public function dashboard() {

        $parentUser = $this->getUser();

        return $this->render(
            'parent-dashboard/base.html.twig',
            [
                'user' => $parentUser,
            ]
        );
    }

    /**
     * @Route("dashboard/nouvel-enfant", name="dashboard-add-child")
     * @param Request $request
     * @return Response
     */
    public function createChild(Request $request, EntityManagerInterface $em) {

        $user = $this->getUser();

        $newChild = new ChildUser();
        $childForm = $this->createForm(ChildUserType::class, $newChild);

        $childForm->handleRequest($request);

        if ($childForm->isSubmitted() && $childForm->isValid()) {
            $newChild->setParent($user);
//            $user->addChild($newChild);

            $em->persist($newChild);
            $em->flush();
        }

        return $this->render(
            'parent-dashboard/pages/addChild.html.twig',
            [
                'user' => $user,
                'childForm' => $childForm->createView(),
            ]
        );
    }

}