<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ApiFilter(OrderFilter::class, properties: ['id', 'firstName', 'lastName'])]
#[ApiFilter(SearchFilter::class, properties: ['firstName', 'lastName'])]
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Your first name must be at least {{ limit }} characters long')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.', )]
    private ?string $email = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $favorite = null;

    #[ORM\OneToOne(targetEntity: ContactProfilePhoto::class, mappedBy: 'contact', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?ContactProfilePhoto $contactProfilePhoto = null;

    #[ORM\OneToMany(targetEntity: ContactPhone::class, mappedBy: 'contact', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $contactPhones;

    public function __construct()
    {
        $this->contactPhones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFavorite(): ?bool
    {
        return $this->favorite;
    }

    public function setFavorite(bool $favorite): void
    {
        $this->favorite = $favorite;
    }

    public function getContactProfilePhoto(): ?ContactProfilePhoto
    {
        return $this->contactProfilePhoto;
    }

    public function setContactProfilePhoto(?ContactProfilePhoto $contactProfilePhoto): void
    {
        $this->contactProfilePhoto = $contactProfilePhoto;
    }

    /**
     * @return Collection<ContactPhone> $contactPhones
     */
    public function getContactPhones()
    {
        return $this->contactPhones;
    }

    /**
     * @param array<ContactPhone>|ArrayCollection $contactPhones
     */
    public function setContactPhones($contactPhones): void
    {
        if (is_array($contactPhones)) {
            $contactPhones = new ArrayCollection($contactPhones);
        }

        $this->contactPhones = $contactPhones;
    }
}
