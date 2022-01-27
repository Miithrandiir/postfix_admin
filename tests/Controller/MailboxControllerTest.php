<?php

namespace App\Tests\Controller;

use App\Entity\Postfix\Domain;
use App\Entity\Postfix\Mailbox;

class MailboxControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $this->login('admin@test.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        $this->assertResponseIsSuccessful();
        self::assertEquals(25, $crawler->filter("#mailbox_table>tbody tr")->count());
        self::assertEquals(1, $crawler->filter("#mailbox>.box-header>a")->count());
    }

    public function testMailboxCreate()
    {
        $this->login('admin@test.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        $crawler = $this->client->click($crawler->filter("#create_mailbox")->eq(0)->link());
        //dump($crawler);
        $this->assertRouteSame('mailbox_create');
        $this->client->submitForm('Submit', [
            'mailbox[domain]' => 1,
            'mailbox[username]' => 'john.doe',
            'mailbox[password]' => 'My_Awe$ome_Password',
            'mailbox[name]' => '',
            'mailbox[firstname]' => '',
            'mailbox[mailDir]' => '/var/vmail/john.doe/',
            'mailbox[quota]' => '0',
            'mailbox[active]' => true
        ]);
        $this->assertResponseRedirects();
        $this->client->followRedirect();

        self::assertNotNull($this->em->getRepository(Mailbox::class)->findOneBy(['username' => 'john.doe', 'domain' => 1]));
    }

    public function testEdit(): void
    {
        $this->login('admin@test.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        self::assertResponseIsSuccessful();
        $mailbox = $crawler->filter('#mailbox_table>tbody>tr:first-child>td:nth-child(2)>span')->innerText();
        $mailbox_split = explode("@", $mailbox);
        $crawler = $this->client->click($crawler->filter('#mailbox_table>tbody>tr:first-child td:last-child a.edit')->eq(0)->link());
        self::assertResponseIsSuccessful();
        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        self::assertNotNull($domain);
        $mailbox_before_edition = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => $mailbox_split[0], 'domain' => $domain]);
        self::assertNotNull($mailbox_before_edition);

        $this->client->submitForm('Submit', [
            'mailbox[domain]' => $mailbox_before_edition->getDomain()->getId(),
            'mailbox[username]' => "potatoes",
            'mailbox[password]' => "",
            'mailbox[name]' => "Doe",
            'mailbox[firstname]' => "John",
            'mailbox[mailDir]' => "/dev/null",
            "mailbox[quota]" => 456123,
            "mailbox[active]" => true
        ]);

        self::assertResponseRedirects();
        $this->client->followRedirect();
        $this->em->clear("App\Entity\Postfix\Mailbox");
        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        $mailbox_after_edition = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => "potatoes", 'domain' => $domain]);
        self::assertNotNull($mailbox_after_edition);


        self::assertEquals($mailbox_before_edition->getId(), $mailbox_after_edition->getId());
        self::assertEquals($domain->getId(), $mailbox_after_edition->getDomain()->getId());
        self::assertEquals("potatoes", $mailbox_after_edition->getUsername());
        self::assertEquals($mailbox_before_edition->getPassword(), $mailbox_after_edition->getPassword());
        self::assertEquals("Doe", $mailbox_after_edition->getName());
        self::assertEquals("John", $mailbox_after_edition->getFirstname());
        self::assertEquals("/dev/null", $mailbox_after_edition->getMailDir());
        self::assertEquals(456123, $mailbox_after_edition->getQuota());
        self::assertEquals(true, $mailbox_after_edition->getActive());
        self::assertNotEquals($mailbox_before_edition->getDateModified(), $mailbox_after_edition->getDateModified());
    }

    public function testDeactivateMailbox(): void
    {
        $this->login('admin@test.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        self::assertResponseIsSuccessful();
        $mailboxHTML = $crawler->filter('#mailbox_table>tbody>tr:first-child>td:nth-child(2)>span')->innerText();
        $mailbox_split = explode("@", $mailboxHTML);

        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        self::assertNotNull($domain);
        $mailbox = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => $mailbox_split[0], 'domain' => $domain]);
        self::assertNotNull($mailbox);

        self::assertTrue($mailbox->getActive());

        //Get Deactivation button
        $crawler = $this->client->click($crawler->filter('#mailbox_table>tbody>tr:first-child td:last-child a.deactivate')->eq(0)->link());
        self::assertResponseRedirects();
        $crawler = $this->client->followRedirect();

        $this->em->clear(Mailbox::class);

        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        self::assertNotNull($domain);
        $mailbox = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => $mailbox_split[0], 'domain' => $domain]);
        self::assertNotNull($mailbox);

        self::assertFalse($mailbox->getActive());

    }

    public function testDeleteMailbox(): void
    {
        $this->login('admin@test.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        self::assertResponseIsSuccessful();
        $mailboxHTML = $crawler->filter('#mailbox_table>tbody>tr:first-child>td:nth-child(2)>span')->innerText();
        $mailbox_split = explode("@", $mailboxHTML);

        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        self::assertNotNull($domain);
        $mailbox = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => $mailbox_split[0], 'domain' => $domain]);
        self::assertNotNull($mailbox);

        //Delete crawler
        $crawler = $this->client->click($crawler->filter('#mailbox_table>tbody>tr:first-child td:last-child a.delete')->eq(0)->link());
        self::assertResponseRedirects();
        $crawler = $this->client->followRedirect();

        $this->em->clear(Mailbox::class);

        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $mailbox_split[1]]);
        self::assertNotNull($domain);
        $mailbox = $this->em->getRepository(Mailbox::class)->findOneBy(['username' => $mailbox_split[0], 'domain' => $domain]);
        self::assertNull($mailbox);
    }
}