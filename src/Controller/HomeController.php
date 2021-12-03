<?php

namespace App\Controller;

use App\Entity\Postfix\Alias;
use App\Entity\Postfix\Domain;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $domains = $this->getUser()->getDomains();

        //count mailboxes
        $mailboxes = 0;
        $aliases = 0;
        foreach ($domains as $domain) {
            $mailboxes += $domain->getMailboxes()->count();
            $aliases += $domain->getAliases()->count();
        }


        $stats = [
            'domains' => $domains->count(),
            'mailboxes' => $mailboxes,
            'aliases' => $aliases,
        ];

        return $this->render('home/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}
