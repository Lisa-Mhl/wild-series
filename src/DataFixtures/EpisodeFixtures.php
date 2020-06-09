<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $episode = new Episode();
            $slugify = new Slugify();
            $episode->setTitle($faker->words($min =1, $max = 5));
            $episode->setNumber($faker->numberBetween($min = 1, $max=20));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween($min = 0, $max = 5)));
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
            $this->addReference('episode' . $i, $episode);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
