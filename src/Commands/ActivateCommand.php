<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;
use Pantheon\Terminus\Exceptions\TerminusException;

/**
 * Class ActivateCommand.
 */
class ActivateCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Activate Autopilot for a given site ID or site name.
     *
     * @command site:autopilot:activate
     * @aliases ap-activate
     * @authorize
     * @filter-output
     *
     * @param string $site_id Site name
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function activate(string $site_id): void
    {
        $site = $this->getSite($site_id);

        try {
            $this->getClient()->activate($site->id);
        } catch (\Throwable $t) {
            throw new TerminusException(
                'Error activating Autopilot: {error_message}',
                ['error_message' => $t->getMessage()]
            );
        }

        $this->log()->success('Autopilot is activated.');
    }
}
