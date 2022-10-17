<?php

namespace Pantheon\TerminusAutopilot\AutopilotApi;

use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\Terminus\Request\Request;

/**
 * Autopilot API Client.
 */
class Client
{
    /**
     * @var \Pantheon\Terminus\Request\Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param \Pantheon\Terminus\Request\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns autopilot settings.
     *
     * @param string $site_id
     *
     * @return array
     *
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSettings(string $site_id): array
    {
        return $this->requestApi($site_id);
    }

    /**
     * Sets autopilot frequency setting.
     *
     * @param string $site_id
     * @param string $frequency
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function setFrequency(string $site_id, string $frequency): void
    {
        $frequency = strtoupper($frequency);
        if (!in_array($frequency, $this->getValidFrequencies(), true)) {
            throw new TerminusException(
                '"{frequency}" is not a valid frequency value. Valid options are: {valid_frequencies}.',
                [
                    'frequency' => $frequency,
                    'valid_frequencies' => implode(', ', $this->getValidFrequencies())
                ]
            );
        }

        $request_body = ['updateFrequency' => $frequency];
        $request_options = [
            'json' => $request_body,
            'method' => 'POST',
        ];

        $this->requestApi($site_id, $request_options);
    }

    /**
     * Returns the list of valid frequency values.
     *
     * @return string[]
     */
    protected function getValidFrequencies(): array
    {
        return [
            'MANUAL',
            'DAILY',
            'WEEKLY',
            'MONTHLY',
        ];
    }

    /**
     * Performs the request to API path.
     *
     * @param string $site_id
     * @param array $options
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function requestApi(string $site_id, array $options = []): array
    {
        $url = sprintf('%s/sites/%s/vrt/settings', $this->getPantheonApiBaseUri(), $site_id);
        $options = array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->request->session()->get('session'),
                ],
                // @todo Remove "verify" flag post-EA, once service is using trusted cert.
                'verify' => false,
            ],
            $options
        );

        $result = $this->request->request($url, $options);
        if ($result->isError()) {
            throw new TerminusException(
                'Failed requesting Autopilot API: {reason}',
                ['reason' => $result->getStatusCodeReason()]
            );
        }

        return (array) $result->getData();
    }

    /**
     * Returns Pantheon API base URI.
     *
     * @return string
     */
    protected function getPantheonApiBaseUri(): string
    {
        $config = $this->request->getConfig();

        return sprintf(
            '%s://%s:%s',
            $config->get('papi_protocol') ?? $config->get('protocol'),
            $this->getHost(),
            $config->get('papi_port') ?? $config->get('port')
        );
    }

    /**
     * Returns Pantheon API host.
     *
     * @return string
     */
    protected function getHost(): string
    {
        $config = $this->request->getConfig();

        if ($config->get('papi_host')) {
            return $config->get('papi_host');
        }

        if ($config->get('host') && false !== strpos($config->get('host'), 'hermes.sandbox-')) {
            return str_replace('hermes', 'pantheonapi', $config->get('host'));
        }

        return 'pantheonapi.svc.pantheon.io';
    }
}
