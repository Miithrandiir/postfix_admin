<?php

namespace App\Tests\Controller;

use App\Entity\Postfix\Domain;


class DomainControllerTest extends WebTestCase
{

    public function testLoadViewPages(): void
    {
        $this->login('admin@domain.tld');

        $this->client->request('GET', '/domain');
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('domain');

    }

    public function testCountDomain(): void
    {
        $this->login('admin@domain.tld');

        $crawler = $this->client->request('GET', '/domain');
        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->user->getDomains()->count(), $crawler->filter('table#domains_table>tbody tr')->count());
    }

    public function testAddDomain(): void
    {
        $this->login('admin@domain.tld');
        $this->client->request('GET', '/domain');
        $crawler = $this->client->clickLink('Add a domain');
        $this->assertRouteSame('domain_create');
        $form = $this->client->submitForm("Submit", [
            'new_domain[domain]' => 'example.tld',
            'new_domain[description]' => 'generate with unit test',
            'new_domain[nb_aliases]' => '0',
            'new_domain[nb_mailboxes]' => '52',
            'new_domain[maxquota]' => '0',
            'new_domain[backupMx]' => false,
            'new_domain[is_active]' => true
        ]);
        $this->assertResponseRedirects();

        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => 'example.tld']);
        $this->assertEquals('example.tld', $domain->getDomain());
        $this->assertEquals('generate with unit test', $domain->getDescription());
        $this->assertEquals(0, $domain->getNbAliases());
        $this->assertEquals(52, $domain->getNbMailboxes());
        $this->assertEquals(0, $domain->getMaxQuota());
        $this->assertEquals(false, $domain->getBackupMx());
        $this->assertEquals(true, $domain->getIsActive());
    }

    public function testDeleteDomain(): void
    {
        $this->login('admin@domain.tld');
        $crawler = $this->client->request('GET', '/domain');
        $domain_name = $crawler->filter("table#domains_table>tbody>tr:first-child td:nth-child(2) span")->getNode(0)->textContent;
        $this->client->click($crawler->filter("table#domains_table>tbody>tr:first-child td:last-child a:last-child")->eq(0)->link());

        $always_exist = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $domain_name]);

        $this->assertEquals(null, $always_exist);

        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertRouteSame('domain');

    }

    public function testDisableDomain(): void
    {
        $this->login('admin@domain.tld');
        $crawler = $this->client->request('GET', '/domain');
        $domain_name = $crawler->filter("table#domains_table>tbody>tr:first-child td:nth-child(2) span")->getNode(0)->textContent;
        $this->client->click($crawler->filter("table#domains_table>tbody>tr:first-child td:last-child a:nth-child(2)")->eq(0)->link());
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->em->clear('App\Entity\Postfix\Domain');
        $domain = $this->em->getRepository(Domain::class)->findOneBy(['domain' => $domain_name]);
        $this->assertNotNull($domain);
        // In fixtures, by default all domains are activate !
        $this->assertEquals(false, $domain->getIsActive());
    }

    public function testEditDomain(): void
    {
        $this->login('admin@domain.tld');
        $crawler = $this->client->request('GET', '/domain');
        $domain_name = $crawler->filter("table#domains_table>tbody>tr:first-child td:nth-child(2) span")->getNode(0)->textContent;
        $this->client->click($crawler->filter("table#domains_table>tbody>tr:first-child td:last-child a:nth-child(3)")->eq(0)->link());
        $this->assertRouteSame('domain_edit');

        $repository = $this->em->getRepository(Domain::class);

        $domain = $repository->findOneBy(['domain' => $domain_name]);
        $this->assertNotNull($domain);
        $crawler = $this->client->submitForm('Submit', [
            'new_domain[domain]' => $domain_name,
            'new_domain[description]' => 'generate with unit test',
            'new_domain[nb_aliases]' => $domain->getNbAliases(),
            'new_domain[nb_mailboxes]' => '0',
            'new_domain[maxquota]' => $domain->getMaxQuota(),
            'new_domain[backupMx]' => true,
            'new_domain[is_active]' => $domain->getIsActive()
        ]);
        self::assertResponseRedirects();
        $this->client->followRedirect();
        $this->em->clear("App\Entity\Postfix\Domain");
        $domainTest = $repository->findOneBy(['domain' => $domain_name]);
        self::assertNotNull($domainTest);
        self::assertEquals($domain_name, $domainTest->getDomain());
        self::assertEquals('generate with unit test', $domainTest->getDescription());
        self::assertEquals(0, $domainTest->getNbMailboxes());
        self::assertEquals(true, $domainTest->getBackupMx());
    }

    public function testViewDomain(): void
    {
        $this->login('admin@domain.tld');
        $crawler = $this->client->request('GET', '/domain');
        $domain_name = $crawler->filter("table#domains_table>tbody>tr:first-child td:nth-child(2) span")->getNode(0)->textContent;
        $crawler = $this->client->click($crawler->filter("table#domains_table>tbody>tr:first-child td:last-child a:nth-child(1)")->eq(0)->link());
        $this->assertRouteSame('domain_view');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(10, $crawler->filter("#table_mailboxes_aliases>tbody>tr")->count());
    }
}