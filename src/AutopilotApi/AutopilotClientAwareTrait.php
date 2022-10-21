<?php

namespace Pantheon\TerminusAutopilot\AutopilotApi;

use Pantheon\Terminus\Request\RequestAwareTrait;
use Pantheon\TerminusAutopilot\Tests\Functional\Mocks\RequestMock;

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
        if (isset($this->autopilotClient)) {
            return $this->autopilotClient;
        }

        if (getenv('TERMINUS_IS_TESTING_ENV')) {
            return $this->autopilotClient = new Client(new RequestMock());
        }

        return $this->autopilotClient = new Client($this->request());
    }
}
