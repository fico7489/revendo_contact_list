<?php

// tests/Service/NewsletterGeneratorTest.php

namespace App\Tests\Service\EventListeners;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SendEmailForCreatedContactTest extends KernelTestCase
{
    public function testSomething()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $contact = new Contact();
        $contact->setFirstName('test');
        $contact->setLastName('test');
        $contact->setEmail('test@test.com');
        $contact->setFavorite(true);

        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $em->persist($contact);
        $em->flush();

        $emails = $this->getMailerMessages();

        /** @var TemplatedEmail $email */
        $email = $emails[0];

        $this->assertEmailCount(1);
        $this->assertEquals('test@test.com', $email->getTo()[0]->toString());
    }
}
