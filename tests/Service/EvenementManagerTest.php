<?php

namespace App\Tests\Service;

use App\Entity\Evenement;
use App\Entity\Inscription;
use App\Entity\User;
use App\Service\EvenementManager;
use App\Repository\InscriptionRepository;
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class EvenementManagerTest extends TestCase
{
    private function makeManager(): EvenementManager
    {
        $inscRepo = $this->createMock(InscriptionRepository::class);
        $eventRepo = $this->createMock(EvenementRepository::class);
        return new EvenementManager($inscRepo, $eventRepo);
    }

    private function makeEvenement(int $capacite, array $inscriptions): Evenement
    {
        $e = new Evenement();
        $e->setCapaciteMax($capacite);
        $ref = new \ReflectionProperty(Evenement::class, 'inscriptions');
        $ref->setAccessible(true);
        $ref->setValue($e, new ArrayCollection($inscriptions));
        return $e;
    }

    private function makeInscription(string $statut, ?User $user = null): Inscription
    {
        $i = new Inscription();
        $i->setStatut($statut);
        if ($user) $i->setParticipant($user);
        return $i;
    }

    public function testGetPlacesRestantes(): void
    {
        $manager = $this->makeManager();
        $e = $this->makeEvenement(10, [
            $this->makeInscription('confirmee'),
            $this->makeInscription('confirmee'),
        ]);
        $this->assertEquals(8, $manager->getPlacesRestantes($e));
    }

    public function testGetPlacesRestantesComplet(): void
    {
        $manager = $this->makeManager();
        $e = $this->makeEvenement(2, [
            $this->makeInscription('confirmee'),
            $this->makeInscription('confirmee'),
        ]);
        $this->assertEquals(0, $manager->getPlacesRestantes($e));
    }

    public function testEstInscritTrue(): void
    {
        $manager = $this->makeManager();
        $user = new User();
        $e = $this->makeEvenement(10, [$this->makeInscription('confirmee', $user)]);
        $this->assertTrue($manager->estInscrit($user, $e));
    }

    public function testEstInscritFalse(): void
    {
        $manager = $this->makeManager();
        $user = new User();
        $autre = new User();
        $e = $this->makeEvenement(10, [$this->makeInscription('confirmee', $autre)]);
        $this->assertFalse($manager->estInscrit($user, $e));
    }

    public function testGetNbInscritsConfirmeesOnly(): void
    {
        $manager = $this->makeManager();
        $e = $this->makeEvenement(10, [
            $this->makeInscription('confirmee'),
            $this->makeInscription('confirmee'),
            $this->makeInscription('en_attente'),
            $this->makeInscription('annulee'),
        ]);
        $this->assertEquals(2, $manager->getNbInscrits($e));
    }
}