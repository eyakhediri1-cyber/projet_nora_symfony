<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementApiTest extends WebTestCase
{
    public function testGetEvenementsRetourne200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/evenements', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostEvenementValideRetourne201(): void
{
    $client = static::createClient();
    $client->request('POST', '/api/evenements', [], [], [
        'CONTENT_TYPE' => 'application/ld+json',
        'HTTP_ACCEPT' => 'application/ld+json',
    ], json_encode([
        'titre' => 'Conférence test sympa',
        'description' => 'Une description suffisamment longue pour passer la validation minimale requise ici.',
        'dateDebut' => '2026-06-01T10:00:00',
        'dateFin' => '2026-06-01T12:00:00',
        'capaciteMax' => 50,
        'categorie' => 'conference',
        'statut' => 'brouillon',
        'lieu' => '/api/lieus/1',
    ]));
    $this->assertResponseStatusCodeSame(201);
}

    public function testPostEvenementTitreVideRetourne422(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/evenements', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ], json_encode([
            'titre' => '',
            'description' => 'Une description suffisamment longue pour passer la validation minimale requise.',
            'dateDebut' => '2026-06-01T10:00:00',
            'dateFin' => '2026-06-01T12:00:00',
            'capaciteMax' => 50,
            'categorie' => 'conference',
        ]));
        $this->assertResponseStatusCodeSame(422);
    }
}