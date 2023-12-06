<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Repository\SeasonRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

            //Puis ici nous demandons à la Factory de nous fournir un Faker
            $faker = Factory::create();
                //Pour appeler la constante déclarée dans ProgramFixtures
                foreach (ProgramFixtures::PROGRAMS as  $programName) {
                    //Boucle pour avoir 10 saisons
                for($i = 1; $i <= 10; $i++) {
                    $season = new Season();
                    //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
                    $season->setNumber($i);
                    $season->setYear($faker->year());
                    $season->setDescription($faker->paragraphs(3, true));    
                    $season->setProgram($this->getReference('program_' .  $programName['title']));
                    $manager->persist($season);
                    $this->addReference("season_" . $i . '_' . $programName['title'], $season);
                }
            }           
            $manager->flush();
        }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ProgramFixtures::class,
        ];
    }
}
