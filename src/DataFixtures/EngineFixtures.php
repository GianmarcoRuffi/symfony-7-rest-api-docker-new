<?php

namespace App\DataFixtures;

use App\Entity\Engine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EngineFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Example Engine 1
        $engine1 = new Engine();
        $engine1->setSerialCode('ABC123');
        $engine1->setName('Engine Model 1');
        $engine1->setHorsepower(200);
        $engine1->setManufacturer('Manufacturer 1');
        $manager->persist($engine1);
        $this->addReference('engine_1', $engine1);

        // Example Engine 2
        $engine2 = new Engine();
        $engine2->setSerialCode('DEF456');
        $engine2->setName('Engine Model 2');
        $engine2->setHorsepower(250);
        $engine2->setManufacturer('Manufacturer 2');
        $manager->persist($engine2);
        $this->addReference('engine_2', $engine2);

        // Example Engine 3
        $engine3 = new Engine();
        $engine3->setSerialCode('GHI789');
        $engine3->setName('Engine Model 3');
        $engine3->setHorsepower(300);
        $engine3->setManufacturer('Manufacturer 3');
        $manager->persist($engine3);
        $this->addReference('engine_3', $engine3);

        $manager->flush();
    }
}
