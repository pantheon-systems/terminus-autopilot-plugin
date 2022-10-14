<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\TerminusAutopilot\Autopilot\AutopilotAwareTrait;

/**
 * Autopilot status check.
 */
abstract class AutopilotCommandBase extends TerminusCommand
{

    use AutopilotAwareTrait;

    /**
     * Construct function to pass the required dependencies.
     */
    public function __construct()
    {
        parent::__construct();
        $this->autopilotInit();
    }

    /**
     * @return void
     */
    protected function setupRequest()
    {
        $this->apiClient()->setRequest($this->request());
    }

}
