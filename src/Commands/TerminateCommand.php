<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;

/**
 * Autopilot status check.
 */
class TerminateCommand extends TerminusCommand
{

    /**
     * @param $site_id
     * @param array $options
     *
     * @return void
     */
    public function terminate($site_id, array $options = ['debug' => false,])
    {
    }
}
