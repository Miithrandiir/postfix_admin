<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Postfix\Domain;
use App\Form\NewDomainType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        if (!$this->isGranted("ROLE_CREATE"))
            return $this->redirectToRoute('domain');

        $domain = new Domain();
        $domainForm = $this->createForm(NewDomainType::class, $domain);

        $domainForm->handleRequest($request);

        if ($domainForm->isSubmitted() && $domainForm->isValid()) {

            $domain->setDateCreated(new \DateTime());
            $domain->setDateModified(new \DateTime());
            $domain->setUser($this->getUserOrThrow());

            $em = $managerRegistry->getManager();
            $em->persist($domain);
            $em->flush();
            $this->addFlash('success', 'You have successfully create a new domain !');
            return $this->redirectToRoute('domain');
        }


        return $this->render('domain/create.html.twig', [
            'form' => $domainForm->createView(),
        ]);
    }

    #[Route('/domain/view/{id}', name: 'domain_view')]
    public function view(int $id, ManagerRegistry $managerRegistry): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();
        $domain = array_filter($domains->toArray(), function (Domain $v) use ($id) {
            return $v->getId() == $id;
        });

        if (sizeof($domain) === 0) {

            if (!$this->isGranted("ROLE_VIEW_ALL")) {
                return $this->redirectToRoute('domain');
            }

            $domain = $managerRegistry->getRepository(Domain::class)->findBy(['id' => $id]);
            if (sizeof($domain) === 0) {
                return $this->redirectToRoute('domain');
            }
        }

        return $this->render('domain/view.html.twig', [
            'domain' => $domain[array_key_first($domain)],
        ]);
    }

    #[Route('/domain/activation/{id}', name: 'domain_activation')]
    public function activation(int $id, ManagerRegistry $managerRegistry): Response
    {
        if (!$this->isGranted('ROLE_DEACTIVATE')) {
            $this->addFlash('error', 'You don\'t have suffisent permissions');
            return $this->redirectToRoute('domain');
        }

        $domains = $this->getUserOrThrow()->getDomains();
        $domain = array_filter($domains->toArray(), function (Domain $v) use ($id) {
            return $v->getId() == $id;
        });

        //if no domain found => Check in DB, but first we need to check if the user is allowed to deactivate all domains
        if (sizeof($domain) === 0) {

            if (!$this->isGranted('ROLE_DEACTIVATE_ALL')) {
                $this->addFlash('error', 'You don\'t have suffisent permissions');
                return $this->redirectToRoute('domain');
            }

            $em = $managerRegistry->getManager();
            $domain = $em->getRepository(Domain::class)->find($id);
            $domain->setIsActive(!$domain->getIsActive());
            $em->flush();


        } else {
            $domain[array_key_first($domain)]->setIsActive(!$domain[array_key_first($domain)]->getIsActive());
            $managerRegistry->getManager()->flush();
        }

        $this->addFlash('success', 'The domain has been successfully updated !');
        return $this->redirectToRoute('domain');
    }
}
