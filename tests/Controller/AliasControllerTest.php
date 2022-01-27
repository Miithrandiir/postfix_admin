<?php

namespace App\Tests\Controller;

use App\Entity\Postfix\Alias;
use App\Entity\Postfix\AliasDomain;

class AliasControllerTest extends WebTestCase
{
    public function testMailboxAliases()
    {
        $this->login("admin@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        self::assertEquals(25, $crawler->filter('#mailbox_aliases_table>tbody tr')->count());

        $this->login("user@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        self::assertEquals(0, $crawler->filter('#mailbox_aliases_table>tbody tr')->count());
    }

    public function testDomainAliases()
    {
        $this->login("admin@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        self::assertEquals(1, $crawler->filter('#table_domain_aliases>tbody tr')->count());

        $this->login("user@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        self::assertEquals(0, $crawler->filter('#table_domain_aliases>tbody tr')->count());
    }

    public function testDeactivateMailboxAlias()
    {
        $this->login("admin@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        $mailbox_alias_id = $crawler->filter('#mailbox_aliases_table>tbody>tr:first-child>td:first-child')->innerText();
        self::assertGreaterThan(0, $mailbox_alias_id);
        $mailbox_alias = $this->em->getRepository(Alias::class)->find($mailbox_alias_id);
        self::assertNotNull($mailbox_alias);
        self::assertTrue($mailbox_alias->getIsActive());
        $this->client->click($crawler->filter('#mailbox_aliases_table>tbody>tr:first-child>td:last-child>.deactivate')->eq(0)->link());
        self::assertResponseRedirects('/alias');
        $this->client->followRedirect();
        $this->em->clear(Alias::class);
        $mailbox_alias = $this->em->getRepository(Alias::class)->find($mailbox_alias_id);
        self::assertNotNull($mailbox_alias);
        self::assertFalse($mailbox_alias->getIsActive());
    }

    public function testDeactivateDomainAlias()
    {
        $this->login("admin@test.tld");
        $crawler = $this->client->request('GET', '/alias');
        self::assertResponseIsSuccessful();
        $domain_alias_id = $crawler->filter('#table_domain_aliases>tbody>tr:first-child>td:first-child')->innerText();
        self::assertGreaterThan(0, $domain_alias_id);
        $domain_alias = $this->em->getRepository(AliasDomain::class)->find($domain_alias_id);
        self::assertNotNull($domain_alias);
        self::assertTrue($domain_alias->getIsActive());
        $this->client->click($crawler->filter('#table_domain_aliases>tbody>tr:first-child>td:last-child>.deactivate')->eq(0)->link());
        self::assertResponseRedirects('/alias');
        $this->client->followRedirect();
        $this->em->clear(AliasDomain::class);
        $domain_alias = $this->em->getRepository(AliasDomain::class)->find($domain_alias_id);
        self::assertNotNull($domain_alias);
        self::assertFalse($domain_alias->getIsActive());
    }
}