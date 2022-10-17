<?php

namespace Pantheon\TerminusAutopilot\AutopilotApi;

use Pantheon\Terminus\Request\RequestAwareTrait;

/**
 * Class AutopilotClientAwareTrait.
 *
 * @package \Pantheon\TerminusAutopilot\AutopilotApi
 */
trait AutopilotClientAwareTrait
{
    use RequestAwareTrait;

    /**
     * @var \Pantheon\TerminusAutopilot\AutopilotApi\Client
     */
    protected Client $autopilotClient;

    /**
     * Return the AutopilotApi object.
     *
     * @return \Pantheon\TerminusAutopilot\AutopilotApi\Client
     */
    public function getClient(): Client
    {
        if ($this->autopilotClient) {
            return $this->autopilotClient;
        }

        return $this->autopilotClient = new Client($this->request());
    }
}
