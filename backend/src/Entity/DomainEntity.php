<?php

namespace App\Entity;

use App\Repository\DomainEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: DomainEntityRepository::class)]
class DomainEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $protocol = null;

    #[ORM\Column(length: 64)]
    private ?string $domain = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $port = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: DomainPathEntity::class)]
    private Collection $domainPaths;

    public function __construct()
    {
        $this->domainPaths = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProtocol(): ?int
    {
        return $this->protocol;
    }

    public function setProtocol(int $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

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

    /**
     * @return Collection<int, DomainPathEntity>
     */
    public function getDomainPaths(): Collection
    {
        return $this->domainPaths;
    }

    public function addDomainPath(DomainPathEntity $domainPath): self
    {
        if (!$this->domainPaths->contains($domainPath)) {
            $this->domainPaths->add($domainPath);
            $domainPath->setDomain($this);
        }

        return $this;
    }

    public function removeDomainPath(DomainPathEntity $domainPath): self
    {
        if ($this->domainPaths->removeElement($domainPath)) {
            // set the owning side to null (unless already changed)
            if ($domainPath->getDomain() === $this) {
                $domainPath->setDomain(null);
            }
        }

        return $this;
    }
}
