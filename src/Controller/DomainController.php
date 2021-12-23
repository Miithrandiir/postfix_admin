<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Postfix\Domain;
use App\Form\NewDomainType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DomainController extends AbstractController
{
    #[Route('/domain', name: 'domain')]
    public function index(): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();

        return $this->render('domain/index.html.twig', [
            'domains' => $domains,
        ]);
    }

    #[Route('/domain/create', name: 'domain_create')]
    public function create(): Response
    {
        $domain = new Domain();
        $domainForm = $this->createForm(NewDomainType::class, $domain);

        return $this->render('domain/create.html.twig', [
            'form' => $domainForm->createView(),
        ]);
    }

    #[Route('/domain/view/{id}', name: 'domain_view')]
    public function view(int $id): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();
        $domain = array_filter($domains->toArray(), function (Domain $v) use ($id) {
            return $v->getId() == $id;
        });

        return $this->render('domain/view.html.twig', [
            'domain' => $domain[0],
            'mailbox_alias' => new ArrayCollection(array_merge($domain[0]->getMailboxes()->toArray(), $domain[0]->getAliases()->toArray()))
        ]);
    }
}
