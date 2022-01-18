<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Postfix\Mailbox;
use App\Form\MailboxType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailboxController extends AbstractController
{
    #[Route('/mailbox', name: 'mailbox')]
    public function index(): Response
    {
        $domains = $this->getUserOrThrow()->getDomains();

        return $this->render('mailbox/index.html.twig', [
            'domains' => $domains,
        ]);
    }

    #[Route('/mailbox/create', name: 'mailbox_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $mailbox = new Mailbox();
        $form = $this->createForm(MailboxType::class, $mailbox, ['user_id' => $this->getUserOrThrow()->getId()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Check if he's the owner of the domain
            if ($mailbox->getDomain()->getUser() !== null && $mailbox->getDomain()->getUser()->getId() !== $this->getUserOrThrow()->getId() && !$this->isGranted('ROLE_ADMIN')) {
                $form->addError(new FormError("You don't have suffisant permission"));
            }
            $mailbox->setDateModified(new \DateTimeImmutable());
            $mailbox->setDateCreated(new \DateTimeImmutable());
            $em = $managerRegistry->getManager();
            $em->persist($mailbox);
            $em->flush();
            return $this->redirectToRoute('mailbox');
        }

        return $this->render('mailbox/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
