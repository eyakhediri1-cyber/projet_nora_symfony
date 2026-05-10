<?php

namespace App\DataFixtures;

use App\Entity\TagEvenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagEvenementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [
            ['nom' => 'Networking', 'couleur' => '#0d6efd'],
            ['nom' => 'Tech', 'couleur' => '#6610f2'],
            ['nom' => 'Gratuit', 'couleur' => '#198754'],
            ['nom' => 'Startup', 'couleur' => '#fd7e14'],
            ['nom' => 'Formation', 'couleur' => '#20c997'],
            ['nom' => 'Culture', 'couleur' => '#d63384'],
            ['nom' => 'Sport', 'couleur' => '#dc3545'],
            ['nom' => 'Famille', 'couleur' => '#6f42c1'],
        ];

        foreach ($tags as $index => $data) {

            $tag = new TagEvenement();

            $tag->setNom($data['nom']);
            $tag->setCouleur($data['couleur']);

            $manager->persist($tag);

            $this->addReference('tag_' . $index, $tag);
        }

        $manager->flush();
    }
}