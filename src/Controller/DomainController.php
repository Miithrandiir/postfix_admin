<?php

namespace App\Controller;

use App\Entity\Postfix\Domain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DomainController extends AbstractController
{
    #[Route('/domain', name: 'domain')]
    public function index(): Response
    {
        $domains = $this->getUser()->getDomains();


        return $this->render('domain/index.html.twig', [
            'domains' => $domains,
        ]);
    }
}
