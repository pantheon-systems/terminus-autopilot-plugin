<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

use Pantheon\Terminus\Config\TerminusConfig;
use Pantheon\Terminus\DataStore\FileStore;
use Pantheon\Terminus\Request\Request;
use Pantheon\Terminus\Request\RequestOperationResult;
use Pantheon\Terminus\Session\Session;

class RequestMock extends Request
{
    use MockPayloadAwareTrait;

    public function request($path, array $options = []): RequestOperationResult
    {
        $result = array_merge(
            ['headers' => [], 'status_code_reason' => ''],
            $this->getMockPayload()
        );
        return new RequestOperationResult($result);
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
