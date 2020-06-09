<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

class ActorFixtures extends Fixture implements DependentFixtureInterface

{
    const ACTORS = [
        'Aurélien Andre',
        'Johnny Leger',
        'Lisa Michel',
        'Elhdaj Bah'
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $actor = new Actor();
            $slugify = new Slugify();
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween($min = 0, $max = 5 )));
            $actor->setName($faker->name);
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}