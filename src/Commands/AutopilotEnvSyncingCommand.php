<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;

/**
 * Class AutopilotEnvSyncingCommand.
 */
class AutopilotEnvSyncingCommand extends TerminusCommand
{
    /**
     * Command to enable/disable environment syncing.
     *
     * @command autopilot:syncing
     */
    public function envSyncing()
    {
        $this->log()->notice('env syncing');
    }
}
