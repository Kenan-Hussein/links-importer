<?php

namespace App\Entity;

use App\Repository\UrlEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UrlEntityRepository::class)]
class UrlEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'urls')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DomainPathEntity $domainPath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $queryString = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomainPath(): ?DomainPathEntity
    {
        return $this->domainPath;
    }

    public function setDomainPath(?DomainPathEntity $domainPath): self
    {
        $this->domainPath = $domainPath;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function setQueryString(string $queryString): self
    {
        $this->queryString = $queryString;

        return $this;
    }
}
