<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $program = new Program();
        $program->setTitle('Walking dead');
        $program->setSynopsis('Des zombies envahissent la terre');
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);
        $this->addReference('program_Walking dead', $program);

        $manager->flush();

        $program = new Program();
        $program->setTitle('Arcane');
        $program->setSynopsis('jsp et je m\'en moque ntm LOL');
        $program->setCategory($this->getReference('category_Animation'));
        $manager->persist($program);
        $this->addReference('program_Arcane', $program);


        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
