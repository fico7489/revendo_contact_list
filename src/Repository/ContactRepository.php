<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
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
        $boolQuery = new BoolQuery();

        if (null != $q) {
            $match = new MultiMatch();
            $match->setFuzziness(1);
            $match->setQuery($q);
            $match->setOperator('AND');
            $match->setFields([
                'firstName',
                'lastName',
                'email',
            ]);

            $boolQuery->addShould($match);
        }

        if ($favorite) {
            $fieldQuery = new MatchQuery();
            $fieldQuery->setField('favorite', true);
            $boolQuery->addMust($fieldQuery);
        }

        /** @var Contact[] $results */
        $results = $this->finder->find($boolQuery);

        return $results;
    }
}
