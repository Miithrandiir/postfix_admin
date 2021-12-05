<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AliasController extends AbstractController
{
    #[Route('/alias', name: 'alias')]
    public function index(): Response
    {
        $domains = $this->getUser()->getDomains();

        return $this->render('aliases/index.html.twig', [
            'domains' => $domains,
        ]);
    }
}
