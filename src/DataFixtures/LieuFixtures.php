<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lieux = [
            ["nom" => "Centre de congres", "adresse" => "Avenue Habib Bourguiba", "ville" => "Tunis", "capacite" => 500],
            ["nom" => "Salle polyvalente", "adresse" => "Rue de Marseille", "ville" => "Sfax", "capacite" => 300],
            ["nom" => "Amphitheatre universitaire", "adresse" => "Campus Universitaire", "ville" => "Sousse", "capacite" => 200],
            ["nom" => "Espace coworking", "adresse" => "Lac 2", "ville" => "Tunis", "capacite" => 100],
            ["nom" => "Parc municipal", "adresse" => "Centre Ville", "ville" => "Nabeul", "capacite" => 1000],
        ];

        foreach ($lieux as $index => $data) {
            $lieu = new Lieu();
            $lieu->setNom($data["nom"]);
            $lieu->setAdresse($data["adresse"]);
            $lieu->setVille($data["ville"]);
            $lieu->setCapacite($data["capacite"]);
            $manager->persist($lieu);
            $this->addReference("lieu_" . $index, $lieu);
        }

        $manager->flush();
    }
}
