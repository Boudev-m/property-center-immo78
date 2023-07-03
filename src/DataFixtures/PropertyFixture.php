<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory; // for generate fake datas

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $property = new Property();
            $property
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentence())
                ->setSurface($faker->numberBetween(10, 200))
                ->setRooms($faker->numberBetween(2, 10))
                ->setBedrooms($faker->numberBetween(1, 9))
                ->setFloor($faker->numberBetween(0, 15))
                ->setPrice($faker->numberBetween(100000, 1000000))
                ->setHeat($faker->numberBetween(1, count(Property::HEAT)))
                ->setCity($faker->city())
                ->setAddress($faker->address())
                ->setPostalCode(str_replace(' ', '', $faker->postcode()))
                ->setSold(false);
            $manager->persist($property);
        }
        $manager->flush();
    }
}
