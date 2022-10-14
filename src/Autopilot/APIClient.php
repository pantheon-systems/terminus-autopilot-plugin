<?php

namespace Pantheon\TerminusAutopilot\Autopilot;

use GuzzleHttp\Client;

/**
 *
 */
class APIClient extends Client
{
    /**
     * @var string Base URI of Pantheon API.
     */
    public string $baseUri = 'https://pantheonapi.svc.pantheon.io';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct([
            'base_uri' => $this->baseUri,
            'debug' => true,
            'verify' => false,
        ]);
    }
}
