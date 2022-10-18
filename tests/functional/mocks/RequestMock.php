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
        $mock = getenv('TERMINUS_REQUEST_MOCK');
        return new RequestOperationResult(json_decode($mock, true));
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
