<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;

/**
 * Autopilot status check.
 */
class AutopilotStatusCommand extends TerminusCommand
{
    /**
     * Status check placeholder.
     *
     * @command autopilot:status
     */
    public function statusCheck()
    {
        $this->log()->notice("The status of Autopilot is green.");
    }
}
