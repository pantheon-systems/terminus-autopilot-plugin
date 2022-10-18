<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

use Pantheon\Terminus\Config\TerminusConfig;
use Pantheon\Terminus\DataStore\FileStore;
use Pantheon\Terminus\Request\Request;
use Pantheon\Terminus\Request\RequestOperationResult;
use Pantheon\Terminus\Session\Session;

class RequestMock extends Request
{
    public function request($path, array $options = []): RequestOperationResult
    {
        return new RequestOperationResult([
            'data' => ['updateFrequency' => 'DAILY'],
            'headers' => [],
            'status_code' => 200,
            'status_code_reason' => '',
        ]);
    }

    public function getConfig()
    {
        return new TerminusConfig([]);
    }

    public function session()
    {
        return new Session(new FileStore('/tmp'));
    }
}
