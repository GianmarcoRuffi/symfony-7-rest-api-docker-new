<?php

namespace App\DataFixtures;

use App\Entity\Bike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BikeFixture extends Fixture implements DependentFixtureInterface
{
public function load(ObjectManager $manager)
{
// Example Bike 1
$bike1 = new Bike();
$bike1->setBrand('Example Brand 1');
$bike1->setEngine($this->getReference('engine_1')); // Assuming you've loaded engines with EngineFixture
$bike1->setColor('Red');
$manager->persist($bike1);

// Example Bike 2
$bike2 = new Bike();
$bike2->setBrand('Example Brand 2');
$bike2->setEngine($this->getReference('engine_2')); // Assuming you've loaded engines with EngineFixture
$bike2->setColor('Blue');
$manager->persist($bike2);

// Example Bike 3
$bike3 = new Bike();
$bike3->setBrand('Example Brand 3');
$bike3->setEngine($this->getReference('engine_3')); // Assuming you've loaded engines with EngineFixture
$bike3->setColor('Green');
$manager->persist($bike3);

$manager->flush();
}

public function getDependencies()
{
return [
EngineFixture::class,
];
}
}