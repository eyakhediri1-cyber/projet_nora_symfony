<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // ADMIN

        $admin = new User();
        $admin->setEmail('admin@eventspot.com');
        $admin->setPseudo('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        

        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );

        $manager->persist($admin);

        $this->addReference('user_admin', $admin);

        // ORGANISATEUR 1
        $orga1 = new User();
        $orga1->setEmail('orga1@eventspot.com');
        $orga1->setPseudo('orga1');
        $orga1->setRoles(['ROLE_ORGANISATEUR']);

        $orga1->setPassword(
            $this->passwordHasher->hashPassword($orga1, 'orga123')
        );

        $manager->persist($orga1);

        $this->addReference('user_orga1', $orga1);

        // ORGANISATEUR 2
        $orga2 = new User();
        $orga2->setEmail('orga2@eventspot.com');
        $orga2->setPseudo('orga2');
        $orga2->setRoles(['ROLE_ORGANISATEUR']);

        $orga2->setPassword(
            $this->passwordHasher->hashPassword($orga2, 'orga123')
        );

        $manager->persist($orga2);

        $this->addReference('user_orga2', $orga2);

        // PARTICIPANTS

        $eya = new User();
$eya->setEmail('eyajo5297@gmail.com');
$eya->setPseudo('eyagrissa');
$eya->setRoles(['ROLE_USER']);

$eya->setPassword(
    $this->passwordHasher->hashPassword($eya, 'useradm')
);

$manager->persist($eya);

$this->addReference('user_eya', $eya);
        for ($i = 0; $i < 5; $i++) {

            $user = new User();

            $user->setEmail($faker->unique()->email());
            $user->setPseudo($faker->userName());
            $user->setRoles(['ROLE_USER']);

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'user123')
            );

            $manager->persist($user);

            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}