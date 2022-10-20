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
     * Returns the mock payload.
     *
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
     * Sets the mock payload.
     *
     * @param mixed $payload
     */
    private function setMockPayload($payload): void
    {
        putenv(sprintf('TERMINUS_MOCK_PAYLOAD=%s', json_encode($payload)));
    }
}
