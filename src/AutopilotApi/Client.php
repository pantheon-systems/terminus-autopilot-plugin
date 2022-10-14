<?php

namespace Pantheon\TerminusAutopilot\AutopilotApi;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function getSettings(string $site_id): array
    {
        $url = sprintf('%s/sites/%s/vrt/settings', $this->getPantheonApiBaseUri(), $site_id);
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $this->request->session()->get('session'),
            ],
            // @todo Remove "verify" flag post-EA, once service is using trusted cert.
            'verify' => false,
        ];

        $result = $this->request->request($url, $options);

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
