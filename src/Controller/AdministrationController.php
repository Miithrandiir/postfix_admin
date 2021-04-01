<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Entity\Mailbox;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="administration")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $nb_user = $em->getRepository(User::class)->count([]);
        $nb_domains = $em->getRepository(Domain::class)->count([]);
        $nb_mailboxes = $em->getRepository(Mailbox::class)->count([]);

        return $this->render('administration/index.html.twig', [
            'count' => [
                'user' => $nb_user,
                'domain' => $nb_domains,
                'mailbox' => $nb_mailboxes
            ]
        ]);
    }
}
