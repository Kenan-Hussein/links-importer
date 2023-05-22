<?php

namespace App\Manager;

use App\AutoMapping;
use App\Entity\DomainEntity;
use App\Entity\DomainPathEntity;
use App\Entity\UrlEntity;
use App\Repository\DomainEntityRepository;
use App\Repository\DomainPathEntityRepository;
use App\Request\UrlCreateRequest;
use Doctrine\ORM\EntityManagerInterface;

class UrlManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AutoMapping $autoMapping,
        private DomainEntityRepository $domainEntityRepository,
        private DomainPathEntityRepository $domainPathEntityRepository
    ){}

    public function saveQuery($req)
    {
        assert($req instanceof UrlCreateRequest);

        if ($req->getPath()) {
            if ($req->getQueryString()) {
                $pathUrlEntity = new UrlEntity();

                $domainPathEntity = $this->domainPathEntityRepository->find($req->getDomainPathId());
                $pathUrlEntity->setDomainPath($domainPathEntity);

                $pathUrlEntity->setQueryString($req->getQueryString());

                $this->entityManager->persist($pathUrlEntity);
                $this->entityManager->flush();

                $this->entityManager->clear();
            }
        }
    }

    public function saveDomainPathAndQuery($req)
    {
        assert($req instanceof UrlCreateRequest);

        if ($req->getPath()) {
            $pathEntity = new DomainPathEntity();

            $domainEntity = $this->domainEntityRepository->find($req->getDomainId());
            $pathEntity->setDomain($domainEntity);

            $pathEntity->setPath($req->getPath());

            $this->entityManager->persist($pathEntity);

            if ($req->getQueryString()) {
                $pathUrlEntity = new UrlEntity();
                $pathUrlEntity->setQueryString($req->getQueryString());
                $pathUrlEntity->setDomainPath($pathEntity);

                $this->entityManager->persist($pathUrlEntity);
            }

            $this->entityManager->flush();

            $this->entityManager->clear();
        }
    }

    public function saveAll($req)
    {
        assert($req instanceof UrlCreateRequest);

        $domainEntity = $this->autoMapping->map(UrlCreateRequest::class, DomainEntity::class, $req);
        assert($domainEntity instanceof DomainEntity);

        $this->entityManager->persist($domainEntity);

        if ($req->getPath()) {
            $pathEntity = new DomainPathEntity();
            $pathEntity->setPath($req->getPath());
            $pathEntity->setDomain($domainEntity);

            $this->entityManager->persist($pathEntity);

            if ($req->getQueryString()) {
                $pathUrlEntity = new UrlEntity();
                $pathUrlEntity->setQueryString($req->getQueryString());
                $pathUrlEntity->setDomainPath($pathEntity);

                $this->entityManager->persist($pathUrlEntity);
            }
        }

        $this->entityManager->flush();

        $this->entityManager->clear();
    }
}