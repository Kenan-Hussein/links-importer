<?php

namespace App\MessageHandler;

use App\Constant\UrlSavePattern;
use App\Manager\UrlManager;
use App\Message\UrlMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UrlMessageHandler
{
    public function __construct(private UrlManager $urlManager) {}

    public function __invoke(UrlMessage $urlMessage)
    {
        $request = $urlMessage->getUrlCreateRequest();

        if ($request->getUrlSavePattern() === UrlSavePattern::ALL) {

            $this->urlManager->saveAll($request);

        } elseif ($request->getUrlSavePattern()  === UrlSavePattern::PATH_QUERY) {

            $this->urlManager->saveDomainPathAndQuery($request);

        } elseif ($request->getUrlSavePattern()  === UrlSavePattern::QUERY) {

            $this->urlManager->saveQuery($request);
        }
    }
}