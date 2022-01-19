<?php

namespace App\Tests\Controller;

use App\Entity\Postfix\Mailbox;

class MailboxControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $this->login('admin@domain.tld');
        $crawler = $this->client->request('GET', '/mailbox');
        $this->assertResponseIsSuccessful();
        self::assertEquals(25, $crawler->filter("#mailbox_table>tbody tr")->count());
        self::assertEquals(1, $crawler->filter("#mailbox>.box-header>a")->count());
    }

    public function testMailboxCreate()
    {
        $this->login('admin@domain.tld');
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
}