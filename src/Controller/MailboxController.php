<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailboxController extends AbstractController
{
    #[Route('/mailbox', name: 'mailbox')]
    public function index(): Response
    {
        $domains = $this->getUser()->getDomains();

        return $this->render('mailbox/index.html.twig', [
            'domains' => $domains,
        ]);
    }
}
