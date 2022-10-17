<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class InitializeCommand.
 */
class InitializeCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Command to initialize autopilot.
     *
     * @command autopilot:initialize
     * @aliases ap-init
     * @authorize
     * @filter-output
     */
    public function initialize(string $site_id): void
    {
        $site = $this->getSite($site_id);

        // @todo: implement
    }
}
