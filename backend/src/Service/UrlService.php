<?php

namespace App\Service;

use App\AutoMapping;
use App\Service\ImportCsvService;
use App\Constant\ProtocolConstant;
use App\Constant\UrlSavePattern;
use App\Entity\DomainEntity;
use App\Entity\DomainPathEntity;
use App\Entity\UrlEntity;
use App\Message\UrlMessage;
use App\Repository\DomainEntityRepository;
use App\Request\UploadCsvRequest;
use App\Request\UrlCreateRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UrlService
{
    public function __construct(
        private ImportCsvService $importCsvService,
        private AutoMapping $autoMapping,
        private MessageBusInterface $messageBus,
        private DomainEntityRepository $domainEntityRepository,
        private EntityManagerInterface $entityManager,
    ){ }

    public function handleData(UploadCsvRequest $request): int
    {
        //get urls from csv file
        $urls = $this->importCsvService->import($request->getCsvFile());
        $count = 0;

        foreach ($urls as $url) {
            $analyzedUrl = parse_url($url['url']);

            $req = $this->initRequest($analyzedUrl);

            $domain = $this->domainEntityRepository->findOneBy(['domain' => $req->getDomain()]);

            //if the domain not exist do direct save
            if (! $domain) {
                $this->save($req, UrlSavePattern::ALL);
                $count += 1;
            } else {
                //check if the path exist
                $paths = $domain->getDomainPaths();

                $matchedDomainPaths = $paths->filter(function ($result) use ($req) {
                   return $result->getPath() ===  $req->getPath();
                });

                if ($matchedDomainPaths->count() > 0) {
                    foreach ($matchedDomainPaths as $path) {
                        assert($path instanceof DomainPathEntity);

                        $matchedDomainPathsQueries = $path->getUrls()->filter(function ($result) use ($req) {
                            return $result->getQueryString() ===  $req->getQueryString();
                        });

                        $_parsedQuery = [];
                        $_queryCount = 0;

                        if ($matchedDomainPathsQueries->count() > 0) {
                            foreach ($matchedDomainPathsQueries as $query) {
                                parse_str($query->getQueryString(), $parsedQuery);
                                $_parsedQuery = $parsedQuery;
                                parse_str($req->getQueryString(), $parsedReqQuery);
                                $queryCount = 0;

                                foreach ($parsedQuery as $key => $value) {
                                    if (array_key_exists($key, $parsedReqQuery) && $value === $parsedReqQuery[$key]) {
                                        $queryCount += 1;
                                        $_queryCount = $queryCount;
                                    }
                                }
                            }
                        }

                        if ($_queryCount !== count($_parsedQuery)) {
                            $this->save($req, UrlSavePattern::QUERY , $domain, $path);
                            $count += 1;
                        }
                    }
                } else {
                    $this->save($req, UrlSavePattern::PATH_QUERY, $domain);
                    $count += 1;
                }
            }
        }

        return $count;
    }

    public function save(
        UrlCreateRequest $req,
        $flag = null,
        DomainEntity $_domainEntity = null,
        DomainPathEntity $_domainPathEntity = null
    ): void
    {
        if ($flag === UrlSavePattern::ALL) {

            $req->setUrlSavePattern(UrlSavePattern::ALL);

            $this->messageBus->dispatch(new UrlMessage($req));
            //$this->saveAll($req);
        } elseif ($flag === UrlSavePattern::PATH_QUERY) {

            $req->setUrlSavePattern(UrlSavePattern::PATH_QUERY);
            $req->setDomainId($_domainEntity->getId());

            $this->messageBus->dispatch(new UrlMessage($req));
            //$this->saveDomainPathAndQuery($req, $_domainEntity);
        } elseif ($flag === UrlSavePattern::QUERY) {

            $req->setUrlSavePattern(UrlSavePattern::QUERY);
            $req->setDomainPathId($_domainPathEntity->getId());

            $this->messageBus->dispatch(new UrlMessage($req));
            //$this->saveQuery($req, $_domainPathEntity);
        }
    }

    #region (TEMP)  First use if DB empty
    public function saveQuery($req, $domainPathEntity)
    {
        assert($req instanceof UrlCreateRequest);

        if ($req->getPath()) {
            if ($req->getQueryString()) {
                $pathUrlEntity = new UrlEntity();

                $pathUrlEntity->setQueryString($req->getQueryString());
                $pathUrlEntity->setDomainPath($domainPathEntity);

                $this->entityManager->persist($pathUrlEntity);
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
    }

    public function saveDomainPathAndQuery($req, $domainEntity)
    {
        assert($req instanceof UrlCreateRequest);

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
    #endregion


    public function initRequest(array $analyzedUrl): UrlCreateRequest
    {
        $createUrl = new UrlCreateRequest();

        if ($analyzedUrl['scheme'] === ProtocolConstant::HTTP) {
            $createUrl->setProtocol(ProtocolConstant::HTTP_VALUE);
        } elseif ($analyzedUrl['scheme'] === ProtocolConstant::HTTPS) {
            $createUrl->setProtocol(ProtocolConstant::HTTPS_VALUE);
        }

        $createUrl->setDomain($analyzedUrl['host']);

        if (array_key_exists('path', $analyzedUrl)) {
            $createUrl->setPath($analyzedUrl['path']);
        }
        if (array_key_exists('query', $analyzedUrl)) {
            $createUrl->setQueryString($analyzedUrl['query']);
        }

        if (array_key_exists('port', $analyzedUrl)) {
            $createUrl->setPort($analyzedUrl['port']);
        }

        return $createUrl;
    }

}