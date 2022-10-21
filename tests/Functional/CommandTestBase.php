<?php

namespace Pantheon\TerminusAutopilot\Tests\Functional;

use Pantheon\Terminus\Tests\Functional\TerminusTestBase;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\MockPayloadAwareTrait;

/**
 * Class CommandTestBase.
 *
 * @package \Pantheon\TerminusAutopilot\Tests\Functional
 */
abstract class CommandTestBase extends TerminusTestBase
{
    use MockPayloadAwareTrait;

    /**
     * Sets the payload for RequestMock.
     *
     * @param $value
     * @param string $expected_uri_suffix
     * @param array $expected_request_options
     */
    protected function setRequestMockPayload($value, string $expected_uri_suffix, array $expected_request_options): void
    {
        $context = $this->getRequestMockPayloadContext($expected_uri_suffix, $expected_request_options);
        $this->setMockPayload($value, $context);
    }

    /**
     * Returns the context data for a mock payload.
     *
     * @param string $expected_uri_suffix
     * @param array $expected_request_options
     *
     * @return array
     *
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    protected function getRequestMockPayloadContext(
        string $expected_uri_suffix,
        array $expected_request_options
    ): array {
        $expected_path = sprintf(
            'https://pantheonapi.svc.pantheon.io:443/sites/%s/vrt/%s',
            getenv('TERMINUS_SITE_UUID'),
            $expected_uri_suffix
        );
        $expected_request_options = array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => null,
                ],
                'verify' => false,
            ],
            $expected_request_options
        );

        return [$expected_path, $expected_request_options];
    }
}
