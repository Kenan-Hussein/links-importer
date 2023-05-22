<?php

namespace App\Message;

use App\Request\CreateRaceDetailsRequest;
use phpDocumentor\Reflection\Types\Self_;

class RaceDetailsMessage
{
    private CreateRaceDetailsRequest $request;

    /**
     * @param CreateRaceDetailsRequest $request
     * @return RaceDetailsMessage
     */
    public static function createRaceDetails(CreateRaceDetailsRequest $request): self
    {
        $raceDetailsMessage = new RaceDetailsMessage();
        $raceDetailsMessage->setRequest($request);

        return $raceDetailsMessage;
    }

    /**
     * @return CreateRaceDetailsRequest
     */
    public function getRequest(): CreateRaceDetailsRequest
    {
        return $this->request;
    }

    /**
     * @param CreateRaceDetailsRequest $request
     */
    public function setRequest(CreateRaceDetailsRequest $request): void
    {
        $this->request = $request;
    }
}