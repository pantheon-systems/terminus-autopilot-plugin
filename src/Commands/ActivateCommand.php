<?php

namespace Pantheon\TerminusAutopilot\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Request\RequestAwareInterface;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\TerminusAutopilot\AutopilotApi\AutopilotClientAwareTrait;

/**
 * Class ActivateCommand.
 */
class ActivateCommand extends TerminusCommand implements RequestAwareInterface, SiteAwareInterface
{
    use AutopilotClientAwareTrait;
    use SiteAwareTrait;

    /**
     * Activate Autopilot.
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
        // @todo: remove warning once API implemented and tested.
        $this->log()->warning('This command is not yet implemented or tested!');

        $site = $this->getSite($site_id);

        try {
            $this->getClient()->activate($site_id);
        } catch (\Throwable $t) {
            $this->log()->error(
                'Autopilot did not successfully activate: {error_message}',
                ['error_message' => $t->getMessage()]
            );
            return;
        }

        $this->log()->success('Autopilot is activated.');
    }
}
