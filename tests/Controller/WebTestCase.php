<?php

namespace App\Tests\Controller;

use _PHPStan_e04cc8dfb\Nette\Neon\Exception;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @internal
 * @coversNothing
 */
class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{

    protected KernelBrowser $client;
    protected EntityManagerInterface $em;
    protected User $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        parent::setUp();
    }

    protected function login(string $username)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'admin@test.tld']);
        if ($user === null) {
            throw new Exception("User {$username} has not been found");
        }
        $this->user = $user;
        $this->client->loginUser($user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
    }
}