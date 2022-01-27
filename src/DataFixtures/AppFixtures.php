<?php

namespace App\DataFixtures;

use App\Entity\Postfix\Alias;
use App\Entity\Postfix\AliasDomain;
use App\Entity\Postfix\Domain;
use App\Entity\Postfix\Mailbox;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{

    public const ADMIN_REFERENCE = 'user-admin';
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $factoryPassword = $this->passwordHasherFactory->getPasswordHasher('App\Entity\User');
        $user = new User();
        $user->setUsername("admin@test.tld");
        //Password = test
        $user->setPassword($factoryPassword->hash('test'));
        $user->setRoles(['ROLE_ADMIN']);
        $this->addReference(self::ADMIN_REFERENCE, $user);
        $manager->persist($user);


        $user_without_permissions = new User();
        $user_without_permissions->setUsername("user@test.tld");
        $user_without_permissions->setPassword($factoryPassword->hash('test'));
        $manager->persist($user_without_permissions);

        $domains = [];

        /**
         * Domains creation
         */
        for ($i = 0; $i < 5; ++$i) {
            $domain = new Domain();
            $domain->setIsActive(true);
            $domain->setUser($user);
            $domain->setDateModified(new \DateTime());
            $domain->setDateCreate(new \DateTime());
            $domain->setDescription("My awesome domain");
            $domain->setDomain("test{$i}.fr");
            $domain->setBackupMx(true);
            $domain->setNbAliases(0);
            $domain->setNbMailboxes(0);
            $domain->setMaxQuota(0);
            $domains[] = $domain;
            $manager->persist($domain);
            /**
             * Mailboxes creation
             */
            for ($y = 0; $y < 5; ++$y) {
                $mailbox = new Mailbox();
                $mailbox->setPassword("My Secret password");
                $mailbox->setUsername("user{$y}");
                $mailbox->setDateModified(new \DateTimeImmutable());
                $mailbox->setDateCreated(new \DateTimeImmutable());
                $mailbox->setActive(true);
                $mailbox->setDomain($domain);
                $mailbox->setQuota(0);
                $mailbox->setMailDir("");
                $mailbox->setFirstname("Unit Test Joe");
                $domain->addMailbox($mailbox);
                $manager->persist($mailbox);
            }

            for ($w = 0; $w < 5; ++$w) {
                $alias = new Alias();
                $alias->setDomain($domain);
                $domain->addAlias($alias);
                $alias->setDateCreated(new \DateTimeImmutable());
                $alias->setDateModified(new \DateTimeImmutable());
                $alias->setIsActive(true);
                $alias->setAddress("alias{$w}");
                $alias->setGoto("user{$w}@{$domain->getDomain()}");
                $manager->persist($alias);
            }
        }

        $domainAlias = new AliasDomain();
        $domainAlias->setIsActive(true);
        $domainAlias->setDateModified(new \DateTimeImmutable());
        $domainAlias->setDateCreated(new \DateTimeImmutable());

        $domainAlias->setOrigine($domains[0]);
        $domainAlias->setDestination($domains[1]);
        $domains[0]->addOrigineAlias($domainAlias);
        $domains[1]->addDestinationAlias($domainAlias);


        $manager->persist($domainAlias);


        $manager->flush();
    }
}
