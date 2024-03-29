<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function getUserOrThrow(): User
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $user;
        }

        throw new AccessDeniedException();
    }

    public function permissionsErrorRedirect(string $route): RedirectResponse
    {
        $this->addFlash('error', 'You don\'t have suffisent permissions');

        return $this->redirectToRoute($route);
    }
}
