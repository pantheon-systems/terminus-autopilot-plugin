<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

/**
 * Class MockPayloadAwareTrait.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional\Mocks
 */
trait MockPayloadAwareTrait
{
    /**
     * @return mixed
     *
     * @throws \JsonException
     */
    private function getMockPayload()
    {
        $payload = getenv('TERMINUS_MOCK_PAYLOAD');
        return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $payload
     */
    private function setMockPayload($payload)
    {
        putenv(sprintf('TERMINUS_MOCK_PAYLOAD=%s', json_encode($payload)));
    }
}
