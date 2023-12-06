<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Repository\SeasonRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $season = new Season();
        $season->setNumber(1);
        $season->setProgram($this->getReference('program_Arcane'));
        $season->setDescription('poupiloupoupou');
        $season->setYear('2009');
        $manager->persist($season);
        //... set other season's properties
        $this->addReference('season1_Arcane', $season);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
        ProgramFixtures::class,
        ];
    }
}
