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
        if (!$this->isGranted('ROLE_CREATE')) {
            return $this->permissionsErrorRedirect('domain');
        }

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
        $domain = array_filter($domains->toArray(), fn(Domain $v) => $v->getId() === $id);

        if (\count($domain) === 0) {
            if (!$this->isGranted('ROLE_VIEW_ALL')) {
                return $this->permissionsErrorRedirect('domain');
            }

            $domain = $managerRegistry->getRepository(Domain::class)->findBy(['id' => $id]);
            if (\count($domain) === 0) {
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
            return $this->permissionsErrorRedirect('domain');
        }

        $domains = $this->getUserOrThrow()->getDomains();
        $domainArr = array_filter($domains->toArray(), fn(Domain $v) => $v->getId() === $id);

        //if no domain found => Check in DB, but first we need to check if the user is allowed to deactivate all domains
        if (\count($domainArr) !== 0) {
            $domain = $domainArr[0];
            $domain[array_key_first($domain)]->setIsActive(!$domain[array_key_first($domain)]->getIsActive());
            $managerRegistry->getManager()->flush();
        } else {
            if (!$this->isGranted('ROLE_DEACTIVATE_ALL')) {
                return $this->permissionsErrorRedirect('domain');
            }

            $em = $managerRegistry->getManager();

            $domainObj = $em->getRepository(Domain::class)->find($id);
            if ($domainObj === null) {
                return $this->permissionsErrorRedirect('domain');
            }
            $domainObj->setIsActive(!$domainObj->getIsActive());
            $em->flush();
        }

        $this->addFlash('success', 'The domain has been successfully updated !');

        return $this->redirectToRoute('domain');
    }

    #[Route('domain/edit/{id}', name: 'domain_edit')]
    public function edit(int $id, ManagerRegistry $managerRegistry, Request $request): Response
    {
        if (!$this->isGranted('ROLE_EDIT')) {
            return $this->permissionsErrorRedirect('domain');
        }

        $domains = $this->getUserOrThrow()->getDomains();
        $domain = array_filter($domains->toArray(), fn(Domain $v) => $v->getId() === $id);

        if (\count($domain) === 0) {
            if (!$this->isGranted('ROLE_EDIT_ALL')) {
                return $this->permissionsErrorRedirect('domain');
            }
            $domain = $managerRegistry->getRepository(Domain::class)->find($id);

            if ($domain === null) {
                return $this->redirectToRoute('domain');
            }
        }

        //render Form
        $form = $this->createForm(NewDomainType::class, $domain instanceof Domain ? $domain : $domain[array_key_first($domain)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $em->flush();

            return $this->redirectToRoute('domain');
        }

        return $this->render('domain/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('domain/delete/{id}', name: 'domain_delete')]
    public function delete(int $id, ManagerRegistry $managerRegistry): Response
    {
        if (!$this->isGranted('ROLE_DELETE')) {
            return $this->permissionsErrorRedirect('domain');
        }

        $domains = $this->getUserOrThrow()->getDomains();
        $domain = array_filter($domains->toArray(), fn(Domain $v) => $v->getId() === $id);

        if (\count($domain) === 0) {
            if (!$this->isGranted('ROLE_DELETE_ALL')) {
                return $this->permissionsErrorRedirect('domain');
            }

            $em = $managerRegistry->getManager();
            $domain_obj = $em->getRepository(Domain::class)->find($id);
            if ($domain_obj === null) {
                return $this->redirectToRoute('domain');
            }

            $em->remove($domain_obj);
            $em->flush();
        } else {
            $managerRegistry->getManager()->remove($domain[array_key_first($domain)]);
            $managerRegistry->getManager()->flush();
        }

        return $this->redirectToRoute('domain');
    }
}
