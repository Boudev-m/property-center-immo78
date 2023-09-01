<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    // To hash user password
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user->setUsername('admin');
        $user->setPassword($this->hasher->hashPassword($user, 'admin729'));
        $manager->persist($user);
        $manager->flush();
    }

    // Can load only news fixture with doctrine:fixtures:load --group=USER
    // Fixture must implement FixtureGroupInterface
    public static function getGroups(): array
    {
        return ['USER'];
    }
}
