<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Postfix\Alias;
use App\Entity\Postfix\AliasDomain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AliasController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }


    #[Route('/alias', name: 'alias')]
    public function index(): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();

        return $this->render('aliases/index.html.twig', [
            'domains' => $domains,
        ]);
    }

    #[Route('/alias/domain/deactivate/{id}', name: 'deactivating_alias_domain')]
    public function deactivate_domain(int $id): Response
    {
        if (!$this->isGranted('ROLE_DOMAIN_ALIAS_DEACTIVATE')) {
            return $this->redirectToRoute('alias');
        }

        $domain_alias = $this->managerRegistry->getRepository(AliasDomain::class)->find($id);
        if ($domain_alias === null)
            return $this->redirectToRoute('alias');

        if ($domain_alias->getOrigine()->getUser() !== null && $domain_alias->getDestination()->getUser() !== null) {

            if ($domain_alias->getOrigine()->getUser() !== null && $domain_alias->getOrigine()->getUser()->getId() === $this->getUserOrThrow()->getId() && $domain_alias->getDestination()->getUser() !== null && $domain_alias->getDestination()->getUser()->getId() === $this->getUserOrThrow()->getId()) {
                //the two domains are owned by the user !
                $domain_alias->setIsActive(!$domain_alias->getIsActive());
                $this->managerRegistry->getManager()->flush();
                return $this->redirectToRoute('alias');
            }

        }
        // If we are here the user doesn't own one of these domains ! need to check his privileges
        if ($this->isGranted('ROLE_DOMAIN_ALIAS_ALL')) {
            $domain_alias->setIsActive(!$domain_alias->getIsActive());
            $this->managerRegistry->getManager()->flush();
            return $this->redirectToRoute('alias');
        }

        return $this->redirectToRoute('alias');
    }

    #[Route('/alias/mailbox/deactivate/{id}', name: 'deactivating_alias_mailbox')]
    public function deactivate_mailbox(int $id): Response
    {
        if (!$this->isGranted('ROLE_MAILBOX_ALIAS_DEACTIVATE')) {
            return $this->redirectToRoute('alias');
        }
        /** @var Alias|null $mailbox_alias */
        $mailbox_alias = $this->managerRegistry->getRepository(Alias::class)->find($id);
        if ($mailbox_alias === null)
            return $this->redirectToRoute('alias');

        if ($mailbox_alias->getDomain() !== null) {

            if ($mailbox_alias->getDomain()->getUser() !== null && $mailbox_alias->getDomain()->getUser()->getId() === $this->getUserOrThrow()->getId()) {
                //the domain is owned by the user !
                $mailbox_alias->setIsActive(!$mailbox_alias->getIsActive());
                $this->managerRegistry->getManager()->flush();
                return $this->redirectToRoute('alias');
            }

        }
        // If we are here the user doesn't own one of these domains ! need to check his privileges
        if ($this->isGranted('ROLE_MAILBOX_ALIAS_ALL')) {
            $mailbox_alias->setIsActive(!$mailbox_alias->getIsActive());
            $this->managerRegistry->getManager()->flush();
            return $this->redirectToRoute('alias');
        }

        return $this->redirectToRoute('alias');
    }

    #[Route('/alias/mailbox/delete/{id}', name: 'delete_alias_mailbox')]
    public function delete_mailbox_alias(int $id): Response
    {
        if (!$this->isGranted("ROLE_MAILBOX_ALIAS_DELETE")) {
            return $this->redirectToRoute('alias');
        }

        $mailbox_alias = $this->managerRegistry->getRepository(Alias::class)->find($id);
        if ($mailbox_alias == null) {
            return $this->redirectToRoute('alias');
        }

        # check if the user own the domain or has suffisent permissions
        if ($mailbox_alias->getDomain() !== null && $mailbox_alias->getDomain()->getUser() !== null && $mailbox_alias->getDomain()->getUser()->getId() === $this->getUserOrThrow()->getId() || $this->isGranted("ROLE_MAILBOX_ALIAS_ALL")) {
            $this->managerRegistry->getManager()->remove($mailbox_alias);
            $this->managerRegistry->getManager()->flush();
            return $this->redirectToRoute('alias');
        }

        # do nothing because he doesn't have enough permissions

        return $this->redirectToRoute('alias');

    }

    #[Route('/alias/domain/delete/{id}', name: 'delete_alias_domain')]
    public function delete_domain_alias(int $id): Response
    {
        if (!$this->isGranted("ROLE_DOMAIN_ALIAS_DELETE")) {
            return $this->redirectToRoute('alias');
        }

        $domain_alias = $this->managerRegistry->getRepository(AliasDomain::class)->find($id);
        if ($domain_alias == null) {
            return $this->redirectToRoute('alias');
        }

        # check if the user own the domain or has suffisent permissions
        if ($domain_alias->getDestination()->getUser() !== null && $domain_alias->getDestination()->getUser()->getId() === $this->getUserOrThrow()->getId() || $this->isGranted("ROLE_DOMAIN_ALIAS_ALL")) {
            $this->managerRegistry->getManager()->remove($domain_alias);
            $this->managerRegistry->getManager()->flush();
            return $this->redirectToRoute('alias');
        }

        # do nothing because he doesn't have enough permissions

        return $this->redirectToRoute('alias');

    }
}
