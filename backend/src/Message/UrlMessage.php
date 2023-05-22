<?php

namespace App\Message;

use App\Request\UrlCreateRequest;

class UrlMessage
{
    public function __construct(private UrlCreateRequest $urlCreateRequest) {}

    /**
     * @return UrlCreateRequest
     */
    public function getUrlCreateRequest(): UrlCreateRequest
    {
        return $this->urlCreateRequest;
    }

}