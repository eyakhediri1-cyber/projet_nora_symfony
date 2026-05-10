<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    public function testListeEvenementsRetourne200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/evenements');
        $this->assertResponseIsSuccessful();
    }

    public function testAccueilEstSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testNouveauSansAuthRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/nouveau');
        $this->assertResponseStatusCodeSame(302);
    }
}