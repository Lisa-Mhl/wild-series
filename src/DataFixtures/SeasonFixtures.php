<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $season = new Season();
            $season->setProgram($this->getReference('program_' . $faker->numberBetween($min = 0, $max = 5 )));
            $season->setNumber($faker->numberBetween($min = 1, $max=10));
            $season->setYear($faker->numberBetween($min = 1990, $max = 2020));
            $season->setDescription($faker->text);
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
