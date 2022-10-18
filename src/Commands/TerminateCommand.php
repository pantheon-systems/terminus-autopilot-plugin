<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class TerminateCommand.
 */
class TerminateCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Command to terminate autopilot.
     *
     * @command site:autopilot:terminate
     * @aliases ap-terminate
     * @authorize
     * @filter-output
     */
    public function terminate(string $site_id): void
    {
        $site = $this->getSite($site_id);

        // @todo: implement
    }
}
