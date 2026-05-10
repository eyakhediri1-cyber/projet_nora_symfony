<?php

namespace App\DataFixtures;

use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Entity\TagEvenement;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EvenementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [
            'conference',
            'atelier',
            'meetup',
            'formation',
            'concert'
        ];

        $statuts = [
            'brouillon',
            'publie',
            'complet',
            'annule'
        ];

        for ($i = 0; $i < 15; $i++) {

            $event = new Evenement();

            $dateDebut = $faker->dateTimeBetween('-2 months', '+3 months');
            $dateFin = (clone $dateDebut)->modify('+3 hours');

            $event->setTitre($faker->sentence(3));
            $event->setDescription($faker->paragraph(5));

            $event->setDateDebut($dateDebut);
            $event->setDateFin($dateFin);

            $event->setCapaciteMax(rand(20, 300));

            $event->setPrix(rand(0, 1) ? rand(0, 200) : 0);

            $event->setCategorie(
                $categories[array_rand($categories)]
            );

            $event->setStatut(
                $statuts[array_rand($statuts)]
            );

            

            // LIEU
            $lieu = $this->getReference(
                'lieu_' . rand(0, 4),
                Lieu::class
            );

            $event->setLieu($lieu);

            // ORGANISATEUR
            $orga = $this->getReference(
                rand(0, 1) ? 'user_orga1' : 'user_orga2',
                User::class
            );

            $event->setOrganisateur($orga);

            // TAGS
            $nbTags = rand(1, 4);

            for ($j = 0; $j < $nbTags; $j++) {

                $tag = $this->getReference(
                    'tag_' . rand(0, 7),
                    TagEvenement::class
                );

                $event->addTag($tag);
            }

            $manager->persist($event);

            $this->addReference('event_' . $i, $event);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LieuFixtures::class,
            UserFixtures::class,
            TagEvenementFixtures::class,
        ];
    }
}