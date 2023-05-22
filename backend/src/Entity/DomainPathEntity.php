<?php

namespace App\Entity;

use App\Repository\DomainPathEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: DomainPathEntityRepository::class)]
class DomainPathEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'domainPaths')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DomainEntity $domain = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $path = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'domainPath', targetEntity: UrlEntity::class)]
    private ?Collection $urls = null;

    #[Pure] public function __construct()
    {
        $this->urls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): ?DomainEntity
    {
        return $this->domain;
    }

    public function setDomain(?DomainEntity $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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
     * @return Collection<int, UrlEntity>
     */
    public function getUrls(): Collection
    {
        return $this->urls;
    }

    public function addUrl(UrlEntity $url): self
    {
        if (!$this->urls->contains($url)) {
            $this->urls->add($url);
            $url->setDomainPath($this);
        }

        return $this;
    }

    public function removeUrl(UrlEntity $url): self
    {
        if ($this->urls->removeElement($url)) {
            // set the owning side to null (unless already changed)
            if ($url->getDomainPath() === $this) {
                $url->setDomainPath(null);
            }
        }

        return $this;
    }
}
