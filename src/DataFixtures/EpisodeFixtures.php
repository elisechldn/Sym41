<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements  DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        //Pour appeler la constante déclarée dans ProgramFixtures
        foreach (ProgramFixtures::PROGRAMS as $key=> $programName) {
            //Rappel de seasonFixtures Pour boucler et avoir 10 saisons
            for($i = 1 ; $i <= 10 ; $i++) {
                //Pour avoir 10 épisodes dans chacune des 10 saisons. $e car pas possible d'avoir 2 $i dans une même boucle
                for($e = 1 ; $e <= 10 ; $e++) {    
                    $episode = new Episode();
                    $episode->setTitle($faker->title());
                    $episode->setNumber($e);
                    $episode->setSeason($this->getReference('season_' .  $i . '_' . $programName['title']));
                    $episode->setSynopsis($faker->paragraphs(3,true)); 
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
}

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ProgramFixtures::class,
            SeasonFixtures::class,
        ];
    }
}
