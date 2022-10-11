<?php

namespace Pantheon\TerminusAutopilot\Autopilot;

use GuzzleHttp\Client;

/**
 *
 */
class APIClient extends Client {

  /**
   *
   */
  public function __construct()
  {
    parent::__construct([
      'base_uri' => 'https://pantheonapi.svc.pantheon.io',
      'debug' => true,
      'verify' => false,
    ]);
  }

}
