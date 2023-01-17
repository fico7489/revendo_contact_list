<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $contact = new Contact();
            $contact->setFirstName('firstName-'.$i);
            $contact->setLastName('lastName-'.$i);
            $contact->setEmail('firstName-'.$i.'@revendo.com');
            $contact->setFavorite((bool) rand(0, 1));
            $manager->persist($contact);
        }

        $manager->flush();
    }
}
