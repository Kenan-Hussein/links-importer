<?php


namespace App\Request;


class UrlCreateRequest
{
    private int $protocol = 0;

    private ?int $port = 0;

    private string $domain = '';

    private ?string $path = '';

    private ?string $queryString = '';

    private ?int $domainId;

    private ?int $domainPathId;

    private ?int $urlSavePattern;

    /**
     * @return int|null
     */
    public function getUrlSavePattern(): ?int
    {
        return $this->urlSavePattern;
    }

    /**
     * @param int|null $urlSavePattern
     */
    public function setUrlSavePattern(?int $urlSavePattern): void
    {
        $this->urlSavePattern = $urlSavePattern;
    }

    /**
     * @return int|null
     */
    public function getDomainId(): ?int
    {
        return $this->domainId;
    }

    /**
     * @param int|null $domainEntity
     */
    public function setDomainId(?int $domainEntity): void
    {
        $this->domainId = $domainEntity;
    }

    /**
     * @return int|null
     */
    public function getDomainPathId(): ?int
    {
        return $this->domainPathId;
    }

    /**
     * @param int|null $domainPathEntity
     */
    public function setDomainPathId(?int $domainPathEntity): void
    {
        $this->domainPathId = $domainPathEntity;
    }

    /**
     * @return int
     */
    public function getProtocol(): int
    {
        return $this->protocol;
    }

    /**
     * @param int $protocol
     */
    public function setProtocol(int $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param int|null $port
     */
    public function setPort(?int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string|null
     */
    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    /**
     * @param string|null $queryString
     */
    public function setQueryString(?string $queryString): void
    {
        $this->queryString = $queryString;
    }
}