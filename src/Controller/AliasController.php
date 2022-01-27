<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AliasController extends AbstractController
{
    #[Route('/alias', name: 'alias')]
    public function index(): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();

        return $this->render('aliases/index.html.twig', [
            'domains' => $domains,
        ]);
    }
}
