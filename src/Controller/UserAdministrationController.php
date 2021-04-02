<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdministrationController extends AbstractController
{
    #[Route('/administration/user', name: 'user_administration')]
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findAll();

        return $this->render('administration/user_admin.html.twig', [
            'users' => $user,
        ]);
    }
}
