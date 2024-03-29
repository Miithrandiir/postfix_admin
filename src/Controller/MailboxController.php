<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailboxController extends AbstractController
{
    #[Route('/mailbox', name: 'mailbox')]
    public function index(): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();

        foreach ($domains as $domain) {
            dump($domain);
            dump(\count($domain->getOrigineAlias()));
        }

        return $this->render('mailbox/index.html.twig', [
            'domains' => $domains,
        ]);
    }
}
