<?php

namespace Pantheon\TerminusAutopilot\Autopilot;

/**
 *
 */
trait AutopilotAwareTrait
{

    /**
     * @var APIClient
     */
    protected APIClient $apiClient;

    /**
     * @return void
     */
    protected function autopilotInit()
    {
        $this->apiClient = new APIClient();
    }

    /**
     * @return APIClient
     */
    public function getAutopilotApiClient(): APIClient
    {
        return $this->apiClient;
    }

    /**
     * @param APIClient $APIClient
     *
     * @return void
     */
    public function setAutopilotApiClient(APIClient $APIClient)
    {
        $this->apiClient = $APIClient;
    }

}
