<?php

namespace App\DataFixtures;

use App\Entity\Inscription;
use App\Entity\Evenement;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $statuts = [
            'confirmee',
            'en_attente',
            'annulee'
        ];

        for ($i = 0; $i < 30; $i++) {

            $inscription = new Inscription();

            $inscription->setDateInscription(
                $faker->dateTimeBetween('-3 months', 'now')
            );

            $inscription->setStatut(
                $statuts[array_rand($statuts)]
            );

            $inscription->setCommentaire(
                $faker->optional()->sentence()
            );

            // EVENT
            $event = $this->getReference(
                'event_' . rand(0, 14),
                Evenement::class
            );

            $inscription->setEvenement($event);

            // USER
            $user = $this->getReference(
                'user_' . rand(0, 4),
                User::class
            );

            $inscription->setParticipant($user);

            $manager->persist($inscription);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EvenementFixtures::class,
            UserFixtures::class,
        ];
    }
}