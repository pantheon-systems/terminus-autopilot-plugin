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
    protected Request $request;

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
        return $this->requestApi(sprintf('sites/%s/vrt/settings', $site_id));
    }

    /**
     * Activates Autopilot.
     *
     * @param string $site_id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function activate(string $site_id)
    {
        $request_options = [
            'json' => [
                'id' => '',
                'workspaceId' => '',
                'settings' => [
                    'updateFrequency' => 'WEEKLY',
                ],
                'skip' => false,
            ],
            'method' => 'POST',
        ];

        $this->requestApi(
            sprintf('sites/%s/vrt/initialize', $site_id),
            $request_options
        );
    }

    /**
     * Deactivates Autopilot.
     *
     * @param string $site_id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function deactivate(string $site_id)
    {
        $request_options = [
            'method' => 'DELETE',
        ];

        $this->requestApi(
            sprintf('sites/%s/vrt/terminate', $site_id),
            $request_options
        );
    }

    /**
     * Sets autopilot environment syncing setting.
     *
     * @param string $site_id
     * @param bool $value
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function setEnvSyncing(string $site_id, bool $value): void
    {
        $request_body = [
            'cloneContent' => ['enabled' => $value],
        ];
        $request_options = [
            'json' => $request_body,
            'method' => 'POST',
        ];

        $this->requestApi(
            sprintf('sites/%s/vrt/settings', $site_id),
            $request_options
        );
    }

    /**
     * Returns environment syncing setting.
     *
     * @param string $site_id
     *
     * @return string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function getEnvSyncing(string $site_id): string
    {
        $settings = $this->getSettings($site_id);
        if (!isset($settings['cloneContent'])) {
            throw new TerminusException('Missing "environment syncing" setting');
        }

        $envSyncingSetting = (array) $settings['cloneContent'];
        if ($envSyncingSetting['enabled'] ?? false) {
            return 'enabled';
        }

        return 'disabled';
    }

    /**
     * Returns destination environment setting.
     *
     * @param string $site_id
     *
     * @return string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function getDestination(string $site_id): string
    {
        $settings = $this->requestApi(sprintf('sites/%s/vrt/settings', $site_id));
        if (!isset($settings['deploymentDestination'])) {
            throw new TerminusException('Missing "deployment destination" setting');
        }

        return $settings['deploymentDestination'];
    }

    /**
     * Sets deployment destination setting.
     *
     * @param string $site_id
     * @param string $destination
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function setDestination(string $site_id, string $destination): void
    {
        if (!in_array($destination, $this->getValidDestinations(), true)) {
            throw new TerminusException(
                '"{destination}" is not a valid deployment destination value. Valid options are: {valid_destinations}.',
                [
                    'destination' => $destination,
                    'valid_destinations' => implode(', ', $this->getValidDestinations())
                ]
            );
        }

        $request_body = ['deploymentDestination' => $destination];
        $request_options = [
            'json' => $request_body,
            'method' => 'POST',
        ];

        $this->requestApi(
            sprintf('sites/%s/vrt/settings', $site_id),
            $request_options
        );
    }

    /**
     * Returns run frequency setting.
     *
     * @param string $site_id
     *
     * @return string
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function getFrequency(string $site_id): string
    {
        $settings = $this->requestApi(sprintf('sites/%s/vrt/settings', $site_id));
        if (!isset($settings['updateFrequency'])) {
            throw new TerminusException('Missing "frequency" setting');
        }

        return strtolower($settings['updateFrequency']);
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
        if (!in_array($frequency, $this->getValidFrequencies(), true)) {
            throw new TerminusException(
                '"{frequency}" is not a valid frequency value. Valid options are: {valid_frequencies}.',
                [
                    'frequency' => $frequency,
                    'valid_frequencies' => implode(', ', $this->getValidFrequencies())
                ]
            );
        }

        $request_body = ['updateFrequency' => strtoupper($frequency)];
        $request_options = [
            'json' => $request_body,
            'method' => 'POST',
        ];

        $this->requestApi(
            sprintf('sites/%s/vrt/settings', $site_id),
            $request_options
        );
    }

    /**
     * Returns the list of valid destination values.
     *
     * @return string[]
     */
    protected function getValidDestinations(): array
    {
        return [
            'dev',
            'test',
            'live',
        ];
    }

    /**
     * Returns the list of valid frequency values.
     *
     * @return string[]
     */
    protected function getValidFrequencies(): array
    {
        return [
            'manual',
            'weekly',
            'monthly',
            'daily',
        ];
    }

    /**
     * Performs the request to API path.
     *
     * @param string $path
     * @param array $options
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function requestApi(string $path, array $options = []): array
    {
        $url = sprintf('%s/%s', $this->getPantheonApiBaseUri(), $path);
        $options = array_merge(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->request->session()->get('session'),
                ],
                // Do not convert http errors to exceptions
                'http_errors' => false,
            ],
            $options
        );

        $result = $this->request->request($url, $options);
        $statusCode = $result->getStatusCode();
        $data = $result->getData();
        // If it went ok, just return data.
        if ($statusCode >= 200 && $statusCode < 300) {
            return (array) $result->getData();
        } elseif (!empty($data->error)) {
            // If error was correctly set from backend, throw it.
            throw new TerminusException($data->error);
        }
        throw new TerminusException(
            'An error ocurred. Code: %code. Message: %reason',
            [
                '%code' => $statusCode,
                '%reason' => $result->getStatusCodeReason(),
            ]
        );
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
            '%s://%s:%s/autopilot/v1',
            $config->get('papi_protocol') ?? $config->get('protocol') ?? 'https',
            $this->getHost(),
            $config->get('papi_port') ?? $config->get('port') ?? '443'
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

        return 'api.pantheon.io';
    }
}
