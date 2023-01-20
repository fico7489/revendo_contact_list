<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    private PaginatedFinderInterface $finder;

    public function __construct(PaginatedFinderInterface $finder, ManagerRegistry $registry)
    {
        $this->finder = $finder;

        parent::__construct($registry, Contact::class);
    }

    public function save(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function findBySearchQuery(bool $favorite, ?string $q)
    {
        $query = new Query();
        $query->addSort(['_id' => ['order' => 'desc']]);

        $queryRoot = new BoolQuery();

        if ($q) {
            $queryFilter = new BoolQuery();

            // search by prefix firstName
            $matchPhrase = new Query\MatchPhrasePrefix();
            $matchPhrase->setField('firstName', $q);
            $queryFilter->addShould($matchPhrase);

            // search by prefix lastName
            $matchPhrase = new Query\MatchPhrasePrefix();
            $matchPhrase->setField('lastName', $q);
            $queryFilter->addShould($matchPhrase);

            // multi match by all fields
            $multiMatch = new MultiMatch();
            $multiMatch->setFuzziness(1);
            $multiMatch->setQuery($q);
            $multiMatch->setPrefixLength(1);
            $multiMatch->setFields([
                'firstName',
                'lastName',
                'email',
                'contactPhones.phone',
            ]);
            $queryFilter->addShould($multiMatch);

            $queryRoot->addMust($queryFilter);
        }

        // get only favorites
        if ($favorite) {
            $fieldQuery = new Query\MatchPhrase();
            $fieldQuery->setParam('favorite', true);

            $queryRoot->addMust($fieldQuery);
        }

        $query->setSize(100);
        if ($queryRoot->count() > 0) {
            $query->setQuery($queryRoot);
        }

        /** @var Contact[] $results */
        $results = $this->finder->find($query);

        return $results;
    }
}
