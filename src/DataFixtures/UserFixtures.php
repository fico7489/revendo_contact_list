<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; ++$i) {
            $user = new User();
            $user->setFirstName('first_name-'.$i);
            $user->setLastName('last_name-'.$i);
            $user->setEmail('first_name-'.$i.'@revendo.com');
            $user->setFavorite((bool) rand(0, 1));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
