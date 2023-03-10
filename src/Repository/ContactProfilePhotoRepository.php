<?php

namespace App\Repository;

use App\Entity\ContactProfilePhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactProfilePhoto>
 *
 * @method ContactProfilePhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactProfilePhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactProfilePhoto[]    findAll()
 * @method ContactProfilePhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactProfilePhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactProfilePhoto::class);
    }

    public function save(ContactProfilePhoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContactProfilePhoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
