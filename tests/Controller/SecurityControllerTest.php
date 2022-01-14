<?php

namespace App\Tests\Controller;


class SecurityControllerTest extends WebTestCase
{
    public function testLoginPassed()
    {
        $this->client->request('GET', '/login');
        $this->client->submitForm('Sign in', [
            'username' => 'admin@test.tld',
            'password' => 'test'
        ]);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('home');
    }
}