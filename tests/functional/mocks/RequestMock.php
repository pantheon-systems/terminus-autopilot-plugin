<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

use Pantheon\Terminus\Config\TerminusConfig;
use Pantheon\Terminus\DataStore\FileStore;
use Pantheon\Terminus\Request\Request;
use Pantheon\Terminus\Request\RequestOperationResult;
use Pantheon\Terminus\Session\Session;

/**
 * Class RequestMock.
 *
 * @see \Pantheon\Terminus\Request\Request
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional\Mocks
 */
class RequestMock extends Request
{
    use MockPayloadAwareTrait;

    /**
     * @inheritdoc
     *
     * @throws \JsonException
     */
    public function request($path, array $options = []): RequestOperationResult
    {
        $result = array_merge(
            [
                'status_code' => 200,
                'data' => null,
                'headers' => [],
                'status_code_reason' => '',
            ],
            $this->getMockPayload()
        );
        return new RequestOperationResult($result);
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return new TerminusConfig([]);
    }

    /**
     * @inheritdoc
     */
    public function session()
    {
        return new Session(new FileStore('/tmp'));
    }
}
