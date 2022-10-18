<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional\Mocks;

/**
 * Class MockPayloadAwareTrait.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional\Mocks
 */
trait MockPayloadAwareTrait
{
    private function getMockPayload()
    {
        $payload = getenv('TERMINUS_MOCK_PAYLOAD');
        if (false === $payload) {
            $payload = [];
        }
        return json_decode($payload, true, JSON_THROW_ON_ERROR);
    }

    private function setMockPayload($payload)
    {
        putenv(sprintf('TERMINUS_MOCK_PAYLOAD=%s', json_encode($payload)));
    }
}
