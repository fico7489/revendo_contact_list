<?php

// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\ORM\EntityManager;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ContactTest extends ApiTestCase
{
    // This trait provided by AliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', 'api/contacts');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Contact',
            '@id' => '/api/contacts',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 20,
        ]);
        $this->assertCount(20, $response->toArray()['hydra:member']);

        $idFirst = $response->toArray()['hydra:member'][0]['id'];
        $idSecond = $response->toArray()['hydra:member'][1]['id'];

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $firstName = $lastName = 'dummy';
        $queryBuilder->update('App\Entity\Contact', 'c')
            ->set('c.firstName', ':firstName')
            ->set('c.lastName', ':lastName')
            ->setParameter('firstName', $firstName)
            ->setParameter('lastName', $lastName)
            ->getQuery()
            ->execute();

        $queryBuilder->update('App\Entity\Contact', 'c')
            ->set('c.firstName', ':firstName')
            ->set('c.lastName', ':lastName')
            ->where('c.id = :id')
            ->setParameter('firstName', 'tester')
            ->setParameter('lastName', 'tester')
            ->setParameter('id', $idFirst)
            ->getQuery()
            ->execute();

        $queryBuilder->update('App\Entity\Contact', 'c')
            ->set('c.firstName', ':firstName')
            ->set('c.lastName', ':lastName')
            ->where('c.id = :id')
            ->setParameter('firstName', 'tester')
            ->setParameter('lastName', 'tester2')
            ->setParameter('id', $idSecond)
            ->getQuery()
            ->execute();

        // test filter "firstName"
        $response = static::createClient()->request('GET', 'api/contacts?firstName=tester');
        $this->assertCount(2, $response->toArray()['hydra:member']);
        $responseDate = $response->toArray();
        $this->assertEquals('tester', $responseDate['hydra:member'][0]['lastName']);
        $this->assertEquals('tester2', $responseDate['hydra:member'][1]['lastName']);

        // test sort "firstName"
        $response = static::createClient()->request('GET', 'api/contacts?firstName=tester&order[lastName]=desc');
        $responseDate = $response->toArray();
        $this->assertEquals('tester2', $responseDate['hydra:member'][0]['lastName']);
        $this->assertEquals('tester', $responseDate['hydra:member'][1]['lastName']);
    }
}
