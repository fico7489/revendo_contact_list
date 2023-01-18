<?php

// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Contact;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ContactTest extends ApiTestCase
{
    // This trait provided by AliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

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
    }

    public function testCreateContact(): void
    {
        $response = static::createClient()->request('POST', '/api/contacts', ['json' => [
            'firstName' => 'test',
            'lastName' => 'test',
            'email' => 'test@test.com',
            'favorite' => true,
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testUpdateContact(): void
    {
        $iri = $this->findIriBy(Contact::class, ['id' => 1]);

        static::createClient([], ['headers' => [
            'content-type' => 'application/merge-patch+json',
        ]])->request('PATCH', $iri, [
            'json' => [
                'firstName' => 'new firstName',
                'lastName' => 'string',
                'email' => 'user@example.com',
                'favorite' => true,
                'contactProfilePhoto' => ((array) [
                    '@type' => 'ContactProfilePhoto',
                    'path' => 'test.jpg',
                    'name' => 'test',
                    'size' => 1000,
                    'mimeType' => 'application/jpg',
                    'contact' => '/api/contacts/1',
                ]),
                'contactPhones' => [
                ],
            ]]);

        $this->assertResponseIsSuccessful();
        /*$this->assertJsonContains([
            '@id' => $iri,
            'firstName' => 'new firstName',
        ]);*/
    }
}
