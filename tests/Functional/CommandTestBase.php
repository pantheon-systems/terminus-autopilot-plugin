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
     * @param string $api_uri_suffix
     * @param array $request_options
     *
     * @return array
     *
     * @see \Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock::request()
     */
    protected function getRequestMockPayloadContext(string $api_uri_suffix, array $request_options = []): array
    {
        $uri = sprintf(
            'https://pantheonapi.svc.pantheon.io:443/sites/%s/vrt/%s',
            getenv('TERMINUS_SITE_UUID'),
            $api_uri_suffix
        );
        $request_options = array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => null,
                ],
                'verify' => false,
            ],
            $request_options
        );

        return [$uri, $request_options];
    }
}
