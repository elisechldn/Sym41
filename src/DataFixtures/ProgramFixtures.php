<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        ['title' => 'Arcane', 'category' => 'Animation'],
        ['title' => 'Daredevil', 'category' => 'Action'],
        ['title' => 'Jojo', 'category' => 'Animation'],
        ['title' => 'Kaamelott', 'category' => 'Comédie'],
        ['title' => 'Preacher', 'category' => 'Aventure'],
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

            foreach (self::PROGRAMS as $programName) {
                $program = new Program();
                $program->setTitle($programName['title']);
                $program->setSynopsis($faker->paragraphs(3, true));
                $program->setCategory($this->getReference('category_' . $programName['category']));
                $manager->persist($program);
                $this->addReference("program_" . $programName['title'], $program);
            }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
