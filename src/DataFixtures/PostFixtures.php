<?php

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Purpose : create fake post but linked with a news in DB
        // search news in DB
        $news = $manager->getRepository(News::class)->find(12);
        // check if news exist
        if (!$news) {
            throw new \Exception("The news is not found.");
        }

        $faker = Factory::create('fr_FR');
        $post = new Post();
        $post->setNews($news)
            ->setUserName($faker->userName())
            ->setText($faker->text(300));
        $manager->persist($post);
        $manager->flush();
    }

    // Can load only news fixture with doctrine:fixtures:load --group=POST
    // Fixture must implement FixtureGroupInterface
    public static function getGroups(): array
    {
        return ['POST'];
    }
}
