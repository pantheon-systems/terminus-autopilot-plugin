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
     * @param array $context
     *
     * @return mixed
     *
     * @throws \JsonException
     * @throws \Exception
     */
    protected function getMockPayload(...$context)
    {
        $payloadJson = getenv('TERMINUS_MOCK_PAYLOAD');
        $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);

        if (!$context) {
            return $payload;
        }

        $key = $this->getKey($context);

        if (!isset($payload[$key])) {
            throw new \Exception(
                sprintf('Mock payload not found for context: "%s"', var_export($context, true))
            );
        }

        return $payload[$key];
    }

    /**
     * Sets the mock payload.
     *
     * @param mixed $value
     * @param array $context
     */
    protected function setMockPayload($value, array $context = []): void
    {
        if (!$context) {
            putenv(sprintf('TERMINUS_MOCK_PAYLOAD=%s', json_encode($value)));
            return;
        }

        $payloadJson = getenv('TERMINUS_MOCK_PAYLOAD');
        $payload = json_decode($payloadJson, true) ?: [];

        $payload[$this->getKey($context)] = $value;

        putenv(sprintf('TERMINUS_MOCK_PAYLOAD=%s', json_encode($payload)));
    }

    /**
     * Returns the unique key for the context data.
     *
     * @param array $context
     *
     * @return string
     */
    private function getKey(array $context)
    {
        return sha1(serialize($context));
    }
}
