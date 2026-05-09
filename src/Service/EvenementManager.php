<?php

namespace App\Service;

use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionRepository;

class EvenementManager
{
    public function __construct(
        private InscriptionRepository $inscRepo,
        private EvenementRepository $eventRepo
    ) {}

    public function getNbInscrits(Evenement $e): int
    {
        return count($e->getInscriptions());
    }

    public function getPlacesRestantes(Evenement $e): int
    {
        return $e->getCapaciteMax() - $this->getNbInscrits($e);
    }

    public function estInscrit(User $u, Evenement $e): bool
    {
        foreach ($e->getInscriptions() as $inscription) {
            if ($inscription->getParticipant() === $u) {
                return true;
            }
        }

        return false;
    }

    public function getEvenementsParCategorie(): array
    {
        $events = $this->eventRepo->findAll();

        $stats = [];

        foreach ($events as $event) {
            $cat = $event->getCategorie();

            if (!isset($stats[$cat])) {
                $stats[$cat] = 0;
            }

            $stats[$cat]++;
        }

        return $stats;
    }
}