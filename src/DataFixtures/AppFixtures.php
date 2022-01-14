<?php

namespace App\DataFixtures;

use App\Entity\Postfix\Domain;
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
            $manager->persist($domain);
        }

        $manager->flush();
    }
}
