<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

use Pantheon\Terminus\DataStore\FileStore;
use Pantheon\Terminus\Request\Request;
use Pantheon\Terminus\Request\RequestOperationResult;
use Pantheon\Terminus\Session\Session;

class RequestMock extends Request
{
    public function request($path, array $options = []) : RequestOperationResult
    {
        return new RequestOperationResult([
            'data' => ['updateFrequency' => 'WEEKLY'],
            'headers' => [],
            'status_code' => 200,
            'status_code_reason' => '',
        ]);
    }

    public function getConfig()
    {
        return [];
    }

    public function session()
    {
        $session_store = new FileStore('/tmp');
        return new Session($session_store);
    }

}
