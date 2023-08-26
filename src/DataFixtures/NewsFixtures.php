<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $news = new News();
        $news->setTitle($faker->words(5, true))
            ->setText($faker->text(2000))
            ->setImageName($faker->imageUrl());
        $manager->persist($news);

        $manager->flush();
    }

    // Can load only news fixture with doctrine:fixtures:load --group=NEWS
    // Fixture must implement FixtureGroupInterface
    public static function getGroups(): array
    {
        return ['NEWS'];
    }
}
